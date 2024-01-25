<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExport;
use App\Helpers\HitungPPH;
use App\Models\KaryawanModel;
use App\Models\PPHModel;
use App\Models\TunjanganModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Repository\PenghasilanTidakTeraturRepository;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class BonusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private PenghasilanTidakTeraturRepository $repo;

    public function __construct()
    {
        $this->repo = new PenghasilanTidakTeraturRepository;
    }

    public function index(Request $request)
    {
        // Need permission
        if (!auth()->user()->can('penghasilan - import - bonus')) {
            return view('roles.forbidden');
        }
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $search = $request->get('q');
        $data = $this->repo->getDataBonus($search, $limit, $page);
        return view('bonus.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
        if (!auth()->user()->can('penghasilan - import - bonus - import')) {
            return view('roles.forbidden');
        }
        $tunjangan = TunjanganModel::select('nama_tunjangan','id')->where('kategori','bonus')->where('is_import',1)->get();
        return view('bonus.import',[
            'data_tunjangan' => $tunjangan
        ]);
    }

    public function import()
    {
        // Need permission
        if (!auth()->user()->can('penghasilan - import - bonus - import')) {
            return view('roles.forbidden');
        }
        $tunjangan = TunjanganModel::select('nama_tunjangan','id')->where('kategori','bonus')->where('is_import',1)->get();
        return view('bonus.import',[
            'data_tunjangan' => $tunjangan
        ]);
    }

    public function show($id){

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Need permission
        if (!auth()->user()->can('penghasilan - import - bonus - import')) {
            return view('roles.forbidden');
        }
        $request->validate([
            'upload_csv' => 'required|mimes:xlsx,xls',
            'nip' => 'required',
            'kategori_bonus' => 'required',
            'nominal' => 'required',
        ],[
            'kategori_bonus' => ':attribute harus terisi.'
        ],[
            'kategori_bonus' => 'Kategori',
            'nip' => 'NIP'
        ]);
        try {
            $id_cabang = "";
            if (auth()->user()->hasRole('cabang')) {
                $id_cabang = auth()->user()->kd_cabang;
            } else {
                $id_cabang = "000";
            }

            \DB::beginTransaction();
            $data_nominal = explode(',',$request->get('nominal'));
            $data_nip = explode(',',$request->get('nip'));
            $tunjangan = TunjanganModel::where('id',$request->get('kategori_bonus'))->first();
            for ($i=0; $i < count($data_nip); $i++) {
                DB::table('penghasilan_tidak_teratur')
                ->insert([
                    'nip' => $data_nip[$i],
                    'id_tunjangan' => $tunjangan->id,
                    'nominal' => $data_nominal[$i],
                    'bulan' => (int) Carbon::parse($request->get('tanggal'))->format('m'),
                    'tahun' => Carbon::parse($request->get('tanggal'))->format('Y'),
                    'kd_entitas' => $id_cabang,
                    'created_at' => Carbon::parse($request->get('tanggal'))
                ]);


            }
            \DB::commit();

            // Hitung pph
            DB::beginTransaction();
            for ($i=0; $i < count($data_nip); $i++) {
                $bulan = (int) Carbon::parse($request->get('tanggal'))->format('m');
                $tahun = (int) Carbon::parse($request->get('tanggal'))->format('Y');
                $karyawan = DB::table('mst_karyawan')
                            ->where('nip', $data_nip[$i])
                            ->whereNull('tanggal_penonaktifan')
                            ->first();
                $pph = HitungPPH::getNewPPH58($request->get('tanggal'), (int) $bulan, $tahun, $karyawan);
            }
            DB::commit();

            if(Carbon::parse($request->get('tanggal'))->format('m') == 12 && Carbon::now()->format('d') > 25){
                \DB::beginTransaction();
                $gajiPerBulanController = new GajiPerBulanController;
                foreach($data_nip as $key => $item){
                    $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($request->get('tanggal'))->format('Y'), Carbon::parse($request->get('tanggal'))->format('m'));
                    PPHModel::where('nip', $request->nip)
                        ->where('tahun', Carbon::parse($request->get('tanggal'))->format('Y'))
                        ->where('bulan', 12)
                        ->update([
                            'total_pph' => $pphTerutang,
                            'updated_at' => null
                        ]);
                }
                \DB::commit();
            }

            Alert::success('Berhasil', 'Berhasil menambahkan bonus.');
            return redirect()->route('bonus.index');
        } catch (Exception $th) {
            \DB::rollBack();
            return $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id, Request $request)
    {
        // Need permission
        if (!auth()->user()->can('penghasilan - import - bonus - detail')) {
            return view('roles.forbidden');
        }

        $tgl = $request->get('tanggal');
        $kd_entitas = $request->get('entitas');
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $search = $request->get('q');
        $data = $this->repo->getDetailBonus($search, $limit,$page, $id, $tgl, $kd_entitas);
        $tunjangan = $this->repo->getNameTunjangan($id);
        $nameCabang = $this->repo->getNameCabang($kd_entitas);

        return view('bonus.detail',['data' => $data, 'tunjangan' => $tunjangan, 'nameCabang' => $nameCabang]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function fileExcel() {
        // Need permission
        $filename = Carbon::now()->format('his').'-bonus'.'.'.'xlsx';
        return Excel::download(new KaryawanExport,$filename);
    }

    public function lock(Request $request){
        // Need permission
        if (!auth()->user()->can('penghasilan - lock - bonus')) {
            return view('roles.forbidden');
        }

        $repo = new PenghasilanTidakTeraturRepository;
        $repo->lockBonus($request->all());
        Alert::success('Berhasil lock tunjangan.');
        return redirect()->route('bonus.index');
    }
    public function unlock(Request $request){
        // Need permission
        if (!auth()->user()->can('penghasilan - unlock - bonus')) {
            return view('roles.forbidden');
        }

        $repo = new PenghasilanTidakTeraturRepository;
        $repo->unlockBonus($request->all());
        Alert::success('Berhasil unlock tunjangan.');
        return redirect()->route('bonus.index');
    }

    public function editTunjangan(Request $request)
    {
        // Need permission
        if (!auth()->user()->can('penghasilan - edit - bonus')) {
            return view('roles.forbidden');
        }
        $id = $request->get('idTunjangan');
        $tanggal = $request->get('tanggal');
        $repo = new PenghasilanTidakTeraturRepository;
        $penghasilan = $repo->TunjanganSelected($id);

        return view('bonus.edit-import', [
            'penghasilan' => $penghasilan,
            'old_id' => $id,
            'old_created_at' => $tanggal
        ]);
    }

    public function editsTunjangan(Request $request)
    {
        // return $request;
        if (!auth()->user()->can('penghasilan - edit - bonus')) {
            return view('roles.forbidden');
        }
        $id = $request->get('idTunjangan');

        $tgl = $request->get('tanggal');
        $kd_entitas = $request->get('entitas');
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $search = $request->get('q');
        $data = $this->repo->getDetailBonus($search, $limit, $page, $id, $tgl, $kd_entitas);
        $tunjangan = $this->repo->getNameTunjangan($id);
        $nameCabang = $this->repo->getNameCabang($kd_entitas);

        return view('bonus.edit', ['data' => $data, 'tunjangan' => $tunjangan, 'nameCabang' => $nameCabang]);
    }
    public function editTunjanganPost(Request $request)
    {

        // Need permission
        if (!auth()->user()->can('penghasilan - edit - bonus')) {
            return view('roles.forbidden');
        }
        $request->validate([
            'upload_csv' => 'required|mimes:xlsx,xls',
            'nip' => 'required',
            'kategori_bonus' => 'required',
            'nominal' => 'required',
        ], [
            'kategori_bonus' => ':attribute harus terisi.'
        ], [
            'kategori_bonus' => 'Kategori',
            'nip' => 'NIP'
        ]);
        try {
            \DB::beginTransaction();
            $data_nominal = explode(',', $request->get('nominal'));
            $data_nip = explode(',', $request->get('nip'));
            $tunjangan = TunjanganModel::where('id', $request->get('kategori_bonus'))->first();
            $old_tunjangan = $request->get('old_tunjangan');
            $old_tanggal = $request->get('old_tanggal');
            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
            $datetime = new DateTime($old_tanggal);
            $new_tanggal = $datetime->format('Y-m-d');

            $repo = new PenghasilanTidakTeraturRepository;
            $dataFromCabang = $repo->getCabang($request->get('kdEntitas'));
            $dataCabangCanEdit = $repo->getCabang($kd_entitas);

            if ($request->get('kdEntitas') == $kd_entitas) {
                DB::table('penghasilan_tidak_teratur')
                ->where('id_tunjangan', $old_tunjangan)
                ->where(DB::raw('DATE(created_at)'), $new_tanggal)
                ->delete();
                for ($i = 0; $i < count($data_nip); $i++) {
                    DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $data_nip[$i],
                        'id_tunjangan' => $tunjangan->id,
                        'nominal' => $data_nominal[$i],
                        'bulan' => (int) Carbon::parse($request->get('tanggal'))->format('m'),
                        'tahun' => Carbon::parse($request->get('tanggal'))->format('Y'),
                        'created_at' => Carbon::parse($request->get('tanggal')),
                        'kd_entitas' => $kd_entitas,
                    ]);
                }
                \DB::commit();
            }else{
                Alert::error('Terjadi Kesalahan', 'Cabang '.$dataCabangCanEdit->nama_cabang.' Tidak Bisa Edit data Bonus '.$dataFromCabang->nama_cabang);
                return redirect()->route('bonus.index');
            }

            Alert::success('Berhasil', 'Berhasil edit data bonus.');
            return redirect()->route('bonus.index');
        } catch (Exception $th) {
            \DB::rollBack();
            return $th;
        }
    }

    public function editTunjanganNewPost(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $data_nip = $request->get('nip');
            $item_id = $request->item_id;
            $createdAt = $request->createdAt;
            $tanggal = $request->tanggal;
            $itemLamaId = DB::table('penghasilan_tidak_teratur')
            ->where('penghasilan_tidak_teratur.created_at', $createdAt)->where('id_tunjangan', $request->id_tunjangan)->pluck('id');
            // return $itemLamaId;
            for ($i = 0; $i < count($itemLamaId); $i++) {
                if (is_null($item_id) || !in_array($itemLamaId[$i], $item_id)) {
                    // hapus item yang tidak ada dalam $item_id
                    DB::table('penghasilan_tidak_teratur')->where('id', $itemLamaId[$i])->delete();
                }
            }

            if (is_array($item_id)) {
                for ($i = 0; $i < count($item_id); $i++) {
                    $nominal = str_replace(['Rp', ' ', '.', "\u{A0}"], '', $request->nominal[$i]);
                    DB::table('penghasilan_tidak_teratur')->where('id', $item_id[$i])->update([
                        'nominal' => $nominal
                    ]);
                }
            }

            DB::commit();

            // Hitung pph
            DB::beginTransaction();
            for ($i = 0; $i < count($data_nip); $i++) {
                $bulan = (int) Carbon::parse($createdAt)->format('m');
                $tahun = (int) Carbon::parse($createdAt)->format('Y');
                $karyawan = DB::table('mst_karyawan')
                            ->where('nip', $data_nip[$i])
                            ->whereNull('tanggal_penonaktifan')
                            ->first();
                $pph = HitungPPH::getNewPPH58($createdAt, (int) $bulan, $tahun, $karyawan);
            }
            DB::commit();

            if (Carbon::parse($createdAt)->format('m') == 12 && Carbon::now()->format('d') > 25) {
                \DB::beginTransaction();
                $gajiPerBulanController = new GajiPerBulanController;
                foreach ($data_nip as $key => $item) {
                    $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($createdAt)->format('Y'), Carbon::parse($createdAt)->format('m'));
                    PPHModel::where('nip', $request->nip)
                        ->where('tahun', Carbon::parse($createdAt)->format('Y'))
                        ->where('bulan', 12)
                        ->update([
                            'total_pph' => $pphTerutang,
                            'updated_at' => null
                        ]);
                }
                \DB::commit();
            }

            Alert::success('Success', 'Berhasil edit data penghasilan');
            return redirect()->route('bonus.index');
        } catch (\Exception $e) {
            //  dd($e->getMessage());
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            //  dd($e->getMessage());
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return back();
        }
    }
}
