<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExport;
use App\Helpers\GajiComponent;
use App\Helpers\HitungPPH;
use App\Helpers\LogActivity;
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

        $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;
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
                    'user_id' => auth()->user()->id,
                    'created_at' => Carbon::parse($request->get('tanggal'))
                ]);

            
                // Record to log activity
                $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
                $namaKaryawan = DB::table('mst_karyawan')->where('nip', $data_nip[$i])->first()?->nama_karyawan;
                $activity = "Pengguna <b>$name</b> menambahkan bonus<b>($tunjangan->nama_tunjangan)</b> untuk karyawan atas nama <b>$namaKaryawan</b>.";
                LogActivity::create($activity);
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
                    $karyawan = DB::table('mst_karyawan')
                                ->select('kd_entitas')
                                ->where('nip', $item)
                                ->first();
                    $kd_entitas_karyawan = $karyawan->kd_entitas;
                    $gaji_component = new GajiComponent($kd_entitas_karyawan);
                    $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($request->get('tanggal'))->format('Y'), Carbon::parse($request->get('tanggal'))->format('m'), $gaji_component);
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

         $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;
        $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;
        $data = $this->repo->getDetailBonus($search, $limit,$page, $id, $tgl, $kd_entitas);
        $tunjangan = $this->repo->getNameTunjangan($id);
        $nameCabang = $this->repo->getNameCabang($kd_entitas);
            
        // Record to log activity
        $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
        $activity = "Pengguna <b>$name</b> melihat detail bonus<b>($tunjangan->nama_tunjangan)</b> untuk tanggal $request->tanggal";
        LogActivity::create($activity);

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

         $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;
        $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;
        $data = $this->repo->getEditBonus($search, $limit, $page, $id, $tgl, $kd_entitas);
        $tunjangan = $this->repo->getNameTunjangan($id);
        $nameCabang = $this->repo->getNameCabang($kd_entitas);
        $dataTunjangan = TunjanganModel::where('kategori', 'bonus')->get();
        $tanggal = date("Y-m-d", strtotime($request->tanggal));
        return view('bonus.edit', [
            'data' => $data,
            'tunjangan' => $tunjangan,
            'dataTunjangan' => $dataTunjangan,
            'nameCabang' => $nameCabang,
            'tanggal' => $tanggal
        ]);
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
            $kd_entitas = $request->get('kdEntitas');
            $datetime = new DateTime($old_tanggal);
            $new_tanggal = $datetime->format('Y-m-d');

            $repo = new PenghasilanTidakTeraturRepository;
            $dataFromCabang = $repo->getCabang($request->get('kdEntitas'));
            $dataCabangCanEdit = $repo->getCabang($kd_entitas);

            if ($request->get('kdEntitas') == $kd_entitas) {
                $data_old = DB::table('penghasilan_tidak_teratur')
                ->where('id_tunjangan', $old_tunjangan)
                ->where('created_at', $new_tanggal)
                ->where('user_id', $request->user_id)
                ->pluck('nip')->toArray();
                for ($i = 0; $i < count($data_old); $i++) {
                    DB::table('penghasilan_tidak_teratur')
                    ->where('id_tunjangan', $old_tunjangan)
                        ->where('created_at', $new_tanggal)
                        ->where('user_id', $request->user_id)
                        ->where('nip', $data_old[$i])
                        ->delete();
                }
                // hitung pph
                DB::beginTransaction();
                foreach ($data_old as $key => $item) {
                    $bulan = (int) Carbon::parse($new_tanggal)->format('m');
                    $tahun = (int) Carbon::parse($new_tanggal)->format('Y');
                    $karyawan = DB::table('mst_karyawan')
                        ->where('nip', $item)
                        ->whereNull('tanggal_penonaktifan')
                        ->first();

                    $pph_baru = HitungPPH::getNewPPH58($new_tanggal, (int) $bulan, $tahun, $karyawan);
                }
                DB::commit();

                DB::beginTransaction();
                if (Carbon::parse($new_tanggal)->format('m') == 12 && Carbon::now()->format('d') > 25) {
                    $gajiPerBulanController = new GajiPerBulanController;
                    foreach ($data_old as $key => $item) {
                        $karyawan = DB::table('mst_karyawan')
                            ->select('kd_entitas')
                            ->where('nip', $item)
                            ->first();
                        $kd_entitas_karyawan = $karyawan->kd_entitas;
                        $gaji_component = new GajiComponent($kd_entitas_karyawan);
                        $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($new_tanggal)->format('Y'), Carbon::parse($new_tanggal)->format('m'), $gaji_component);
                        PPHModel::where('nip', $item)
                            ->where('tahun', Carbon::parse($new_tanggal)->format('Y'))
                            ->where('bulan', 12)
                            ->update([
                                'total_pph' => $pphTerutang,
                                'updated_at' => now()
                            ]);
                    }
                }


                for ($i = 0; $i < count($data_nip); $i++) {
                    DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $data_nip[$i],
                        'id_tunjangan' => $tunjangan->id,
                        'nominal' => $data_nominal[$i],
                        'bulan' => (int) Carbon::parse($request->get('tanggal'))->format('m'),
                        'tahun' => Carbon::parse($request->get('tanggal'))->format('Y'),
                        'created_at' => Carbon::parse($request->get('tanggal')),
                        'user_id' => auth()->user()->id,
                        'kd_entitas' => $kd_entitas,
                    ]);
                }
                DB::commit();

                DB::beginTransaction();
                foreach ($data_nip as $key => $item) {
                    $bulan = (int) Carbon::parse($request->get('tanggal'))->format('m');
                    $tahun = (int) Carbon::parse($request->get('tanggal'))->format('Y');
                    $karyawan = DB::table('mst_karyawan')
                        ->where('nip', $item)
                        ->whereNull('tanggal_penonaktifan')
                        ->first();

                    $pph_baru = HitungPPH::getNewPPH58($request->get('tanggal'), (int) $bulan, $tahun, $karyawan);
                }
                DB::commit();

                DB::beginTransaction();
                if (Carbon::parse($request->get('tanggal'))->format('m') == 12 && Carbon::now()->format('d') > 25) {
                    $gajiPerBulanController = new GajiPerBulanController;
                    foreach ($data_nip as $key => $item) {
                        $karyawan = DB::table('mst_karyawan')
                            ->select('kd_entitas')
                            ->where('nip', $item)
                            ->first();
                        $kd_entitas_karyawan = $karyawan->kd_entitas;
                        $gaji_component = new GajiComponent($kd_entitas_karyawan);
                        $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($request->get('tanggal'))->format('Y'), Carbon::parse($request->get('tanggal'))->format('m'), $gaji_component);
                        PPHModel::where('nip', $item)
                            ->where('tahun', Carbon::parse($request->get('tanggal'))->format('Y'))
                            ->where('bulan', 12)
                            ->update([
                                'total_pph' => $pphTerutang,
                                'updated_at' => now()
                            ]);
                    }
                }
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
        DB::beginTransaction();
        try {
            $data_nip = $request->get('nip');
            $item_id = $request->has('item_id') ? $request->get('item_id') : 'null';
            $createdAt = $request->has('createdAt') ? $request->get('createdAt') : null;
            $temp_nip = $request->has('temp_nip') ? $request->get('temp_nip')[0] : null;
            $temp_nip_array = json_decode($temp_nip, true);
            if ($item_id == 'null') {
                for ($i = 0; $i < count($temp_nip_array); $i++) {
                    DB::table('penghasilan_tidak_teratur')
                        ->where('id_tunjangan', $request->get('id_tunjangan'))
                        ->where('bulan', (int) Carbon::parse($createdAt)->format('m'))
                        ->where('tahun', (int) Carbon::parse($createdAt)->format('Y'))
                        ->where('kd_entitas', $request->get('entitas'))
                        ->where('nip', $temp_nip_array[$i])
                        ->delete();
                    DB::commit();
                }

                // Hitung pph
                DB::beginTransaction();
                foreach ($temp_nip_array as $key => $item) {
                    $bulan = (int) Carbon::parse($createdAt)->format('m');
                    $tahun = (int) Carbon::parse($createdAt)->format('Y');
                    $karyawan = DB::table('mst_karyawan')
                        ->where('nip', $item)
                        ->whereNull('tanggal_penonaktifan')
                        ->first();

                    $pph_baru = HitungPPH::getNewPPH58($createdAt, (int) $bulan, $tahun, $karyawan);
                }
                DB::commit();

                DB::beginTransaction();
                if (Carbon::parse($createdAt)->format('m') == 12 && Carbon::now()->format('d') > 25) {
                    $gajiPerBulanController = new GajiPerBulanController;
                    foreach ($temp_nip_array as $key => $item) {
                        $karyawan = DB::table('mst_karyawan')
                                    ->select('kd_entitas')
                                    ->where('nip', $item)
                                    ->first();
                        $kd_entitas_karyawan = $karyawan->kd_entitas;
                        $gaji_component = new GajiComponent($kd_entitas_karyawan);
                        $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($createdAt)->format('Y'), Carbon::parse($createdAt)->format('m'), $gaji_component);
                        PPHModel::where('nip', $item)
                            ->where('tahun', Carbon::parse($createdAt)->format('Y'))
                            ->where('bulan', 12)
                            ->update([
                                'total_pph' => $pphTerutang,
                                'updated_at' => now()
                            ]);
                    }
                }
                DB::commit();
            }
            else {
                $itemLamaId = DB::table('penghasilan_tidak_teratur')
                            ->where('penghasilan_tidak_teratur.created_at', $createdAt)
                            ->where('id_tunjangan', $request->id_tunjangan)
                            ->where('kd_entitas', $request->get('entitas'))
                            ->pluck('id')->toArray();
                // return ['item' => count($item_id), 'item_hapus' => count($itemLamaId)];
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
                            'nominal' => $nominal,
                            'tahun' => date('Y', strtotime($request->tanggal)),
                            'bulan' => date('m', strtotime($request->tanggal)),
                            'created_at' => $request->tanggal,
                            'id_tunjangan' => $request->id_tunjangan_up,
                            'is_lock' => 1
                        ]);
                    }
                }

                DB::commit();

                // Hitung pph
                DB::beginTransaction();
                for ($i = 0; $i < count($data_nip); $i++) {
                    $bulan = (int) Carbon::parse($request->tanggal)->format('m');
                    $tahun = (int) Carbon::parse($request->tanggal)->format('Y');
                    $karyawan = DB::table('mst_karyawan')
                                ->where('nip', $data_nip[$i])
                                ->whereNull('tanggal_penonaktifan')
                                ->first();
                    $pph = HitungPPH::getNewPPH58($request->tanggal, (int) $bulan, $tahun, $karyawan);
                }
                DB::commit();

                if (Carbon::parse($request->tanggal)->format('m') == 12 && Carbon::now()->format('d') > 25) {
                    \DB::beginTransaction();
                    $gajiPerBulanController = new GajiPerBulanController;
                    foreach ($data_nip as $key => $item) {
                        $karyawan = DB::table('mst_karyawan')
                                    ->select('kd_entitas')
                                    ->where('nip', $item)
                                    ->first();
                        $kd_entitas_karyawan = $karyawan->kd_entitas;
                        $gaji_component = new GajiComponent($kd_entitas_karyawan);
                        $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($request->tanggal)->format('Y'), Carbon::parse($request->tanggal)->format('m'), $gaji_component);
                        PPHModel::where('nip', $request->nip)
                            ->where('tahun', Carbon::parse($request->tanggal)->format('Y'))
                            ->where('bulan', 12)
                            ->update([
                                'total_pph' => $pphTerutang,
                                'updated_at' => null
                            ]);
                    }
                    \DB::commit();
                }
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
