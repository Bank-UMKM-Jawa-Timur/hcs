<?php

namespace App\Http\Controllers;

use App\Exports\ExportBiayaDuka;
use App\Exports\ExportBiayaKesehatan;
use App\Exports\ExportBiayaTidakTeratur;
use App\Imports\PenghasilanImport;
use App\Models\GajiPerBulanModel;
use App\Models\ImportPenghasilanTidakTeraturModel;
use App\Models\PPHModel;
use App\Models\TunjanganModel;
use App\Repository\PenghasilanTidakTeraturRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class PenghasilanTidakTeraturController extends Controller
{
    public function getDataKaryawan(Request $request)
    {
        $nip = $request->get('nip');
        $data = DB::table('mst_karyawan')
            ->where('nip', $nip)
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->select('mst_karyawan.*', 'mst_jabatan.nama_jabatan')
            ->first();
        if($data == null){
            return null;
        }
        if($data->status_karyawan == "Tetap"){
            $jabatan = "Pegawai ".$data->status_karyawan . ' ' . $data->nama_jabatan;
        }
        else{
            $jabatan = $data->status_karyawan . ' ' . $data->nama_jabatan;
        }
        $dk = [
            'nip' => $data->nip,
            'nama' => $data->nama_karyawan,
            'jabatan' => $jabatan
        ];

        return response()->json($dk);
    }

    public function upload(Request $request)
    {
        $file = $request->file('upload_csv');
        $import = new PenghasilanImport;
        $row = Excel::toArray($import, $file);
        // dd(count($row[0]));
        $import = $import->import($file);
        Alert::success('Berhasil', 'Berhasil mengimport '.count($row[0]).' data');
        return redirect()->route('import-penghasilan-index');
    }

    public function filter(Request $request)
    {
        $cabang = array();
        $cbg = DB::table('mst_cabang')
            ->select('kd_cabang')
            ->get();
        foreach($cbg as $item){
            array_push($cabang, $item->kd_cabang);
        }

        $tahun = $request->get('tahun');
        $mode = $request->get('mode');
        $nip = $request->get('nip');
        $gaji = array();
        $total_gaji = array();
        $tk = array();
        $ptt = array();
        $bonus = array();
        $total_gj = array();
        $jamsostek = array();
        $pengurang = array();
        $pph_yang_dilunasi = array();
        $tj = [];
        $peng = [];
        $bon = [];
        $w = 0;
        $x = 0;
        $y = 0;
        $z = 0;

        $karyawan = DB::table('mst_karyawan')
            ->where('nip', $nip)
            ->leftJoin('mst_jabatan', 'mst_jabatan.kd_jabatan', 'mst_karyawan.kd_jabatan')
            ->select('mst_karyawan.*', 'mst_jabatan.nama_jabatan')
            ->first();
            
        if (!$karyawan->kd_entitas) {
            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                ->where('kd_cabang', '000')
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                ->first();
            $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                ->where('kd_cabang', '000')
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                ->first();
        }
        else if(in_array($karyawan->kd_entitas, $cabang)){
            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                ->where('mst_profil_kantor.kd_cabang', $karyawan->kd_entitas)
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                ->first();
            $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                ->where('kd_cabang', $karyawan->kd_entitas)
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                ->first();
        } else {
            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                ->where('kd_cabang', '000')
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                ->first();
            $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                ->where('kd_cabang', '000')
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                ->first();
        }
        // $persen_jkk = $hitungan_penambah && $hitungan_penambah->jkk === null ? $hitungan_penambah->jkk : 0;
        if ($hitungan_penambah == null && $hitungan_pengurang == null) {
            $persen_jkk = 0;
            $persen_jht = 0;
            $persen_jkm = 0;
            $persen_kesehatan = 0;
            $persen_jp_penambah = 0;
            $persen_dpp = 0;
            $persen_jp_pengurang = 0;
            $batas_atas = 0;
            $batas_bawah = 0;
            $jp_jan_feb = 0;
            $jp_mar_des = 0;
        }else{
            $persen_jkk = $hitungan_penambah->jkk;
            $persen_jht = $hitungan_penambah->jht;
            $persen_jkm = $hitungan_penambah->jkm;
            $persen_kesehatan = $hitungan_penambah->kesehatan;
            $persen_jp_penambah = $hitungan_penambah->jp;
            $persen_dpp = $hitungan_pengurang->dpp;
            $persen_jp_pengurang = $hitungan_pengurang->jp;
            $batas_atas = $hitungan_penambah->kesehatan_batas_atas;
            $batas_bawah = $hitungan_penambah->kesehatan_batas_bawah;
            $jp_jan_feb = $hitungan_pengurang->jp_jan_feb;
            $jp_mar_des = $hitungan_pengurang->jp_mar_des;
        }

        // Get gaji secara bulanan
        for($i = 1; $i <= 12; $i++){
            $pph = PPHModel::where('nip', $nip)
                ->where('bulan', $i)
                ->where('tahun', $tahun)
                ->first();
            array_push($pph_yang_dilunasi, ($pph != null) ? $pph->total_pph : 0);
            $data = DB::table('gaji_per_bulan')
                    ->select('gaji_per_bulan.*')
                    ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                    ->where('gaji_per_bulan.nip', $nip)
                    ->where('gaji_per_bulan.bulan', $i)
                    ->where('gaji_per_bulan.tahun', $tahun)
                    ->where('batch.status', 'final')
                    ->first();

            $gj[$i - 1] = [
                'gj_pokok' => ($data != null) ? $data->gj_pokok : 0,
                'gj_penyesuaian' => ($data != null) ? $data->gj_penyesuaian : 0,
                'tj_keluarga' => ($data != null) ? $data->tj_keluarga : 0,
                'tj_telepon' => ($data != null) ? $data->tj_telepon : 0,
                'tj_jabatan' => ($data != null) ? $data->tj_jabatan : 0,
                'tj_teller' => ($data != null) ? $data->tj_teller : 0,
                'tj_perumahan' => ($data != null) ? $data->tj_perumahan : 0,
                'tj_kemahalan' => ($data != null) ? $data->tj_kemahalan : 0,
                'tj_pelaksana' => ($data != null) ? $data->tj_pelaksana : 0,
                'tj_kesejahteraan' => ($data != null) ? $data->tj_kesejahteraan : 0,
                'tj_multilevel' => ($data != null) ? $data->tj_multilevel : 0,
                'tj_ti' => ($data != null) ? $data->tj_ti : 0,
                'tj_fungsional' => ($data != null) ? $data->tj_fungsional : 0,
                'tj_transport' => ($data != null) ? $data->tj_transport : 0,
                'tj_pulsa' => ($data != null) ? $data->tj_pulsa : 0,
                'tj_vitamin' => ($data != null) ? $data->tj_vitamin : 0,
                'uang_makan' => ($data != null) ? $data->uang_makan : 0,
            ];

            $total_gj[$i-1] = [
                'gj_pokok' => ($data != null) ? $data->gj_pokok : 0,
                'tj_keluarga' => ($data != null) ? $data->tj_keluarga : 0,
                'tj_jabatan' => ($data != null) ? $data->tj_jabatan : 0,
                'gj_penyesuaian' => ($data != null) ? $data->gj_penyesuaian : 0,
                'tj_perumahan' => ($data != null) ? $data->tj_perumahan : 0,
                'tj_telepon' => ($data != null) ? $data->tj_telepon : 0,
                'tj_pelaksana' => ($data != null) ? $data->tj_pelaksana : 0,
                'tj_kemahalan' => ($data != null) ? $data->tj_kemahalan : 0,
                'tj_kesejahteraan' => ($data != null) ? $data->tj_kesejahteraan : 0,
            ];
            array_push($gaji, $gj[$i-1]);
            array_push($total_gaji, array_sum($total_gj[$i-1]));
            // Get Penghasilan tidak teratur karyawan (exclude bonus)
            $id_ptt = TunjanganModel::where('kategori', 'tidak teratur')
                                    ->orderBy('id')
                                    ->pluck('id');
            $k = 0;
            for($j = 0; $j < count($id_ptt); $j++){
                $penghasilan = DB::table('penghasilan_tidak_teratur')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', $id_ptt[$j])
                    ->where('tahun', $tahun)
                    ->where('bulan', $i)
                    ->first();
                if($data != null){
                    $peng[$k] = ($penghasilan != null) ? $penghasilan->nominal : 0;
                } else {
                    $peng[$k] = 0;
                }
                $k++;
            }
            array_push($ptt, $peng);

            // Get Bonus Karyawan
            $l = 0;
            $id_bonus = TunjanganModel::where('kategori', 'bonus')
                                        ->orderBy('id')
                                        ->pluck('id');

            // for($j = 22; $j <= 24; $j++){
            for($j = 0; $j < count($id_bonus); $j++){
                $bns = DB::table('penghasilan_tidak_teratur')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', $id_bonus[$j])
                    ->where('tahun', $tahun)
                    ->where('bulan', $i)
                    ->first();

                if($data != null){
                    $bon[$l] = ($bns != null) ? $bns->nominal : 0;
                } else{
                    $bon[$l] = 0;
                }
                $l++;
            }
            array_push($bonus, $bon);
        }

        foreach($total_gaji as $key => $item){
            $nominal_jp = ($key > 1) ? $jp_mar_des : $jp_jan_feb;
            // Get Jamsostek
            if($item > 0){
                $jkk = 0;
                $jht = 0;
                $jkm = 0;
                $kesehatan = 0;
                $jp_penambah = 0;
                if($karyawan->tanggal_penonaktifan == null){
                    $jkk = ($persen_jkk / 100) * $item;
                    $jht = ($persen_jht / 100) * $item;
                    $jkm = ($persen_jkm / 100) * $item;
                    $jp_penambah = ($persen_jp_penambah / 100) * $item;
                }

                if($karyawan->jkn != null){
                    if($item > $batas_atas){
                        $kesehatan = ($batas_atas * ($persen_kesehatan / 100));
                    } else if($item < $batas_bawah){
                        $kesehatan = ($batas_bawah * ($persen_kesehatan / 100));
                    } else{
                        $kesehatan = ($item * ($persen_kesehatan / 100));
                    }
                }
                array_push($jamsostek, round($jkk + $jht + $jkm + $kesehatan + $jp_penambah));
            } else {
                array_push($jamsostek, 0);
            }

            // Get Pengurang Bruto
            if($item > 0){
                if($karyawan->status_karyawan == 'IKJP') {
                    array_push($pengurang, ($persen_jp_pengurang / 100) * $item);
                } else{
                    $gj_pokok = $gj[$key]['gj_pokok'];
                    $tj_keluarga = $gj[$key]['tj_keluarga'];
                    $tj_kesejahteraan = $gj[$key]['tj_kesejahteraan'];

                    $dpp = ((($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100));
                    if($item >= $nominal_jp){
                        $dppExtra = $nominal_jp * ($persen_jp_pengurang / 100);
                    } else {
                        $dppExtra = $item * ($persen_jp_pengurang / 100);
                    }
                    // dd($gj_pokok, $tj_keluarga, $tj_kesejahteraan, $dpp, $dppExtra, $dpp + $dppExtra);
                    array_push($pengurang, round($dpp + $dppExtra));
                }
            } else {
                array_push($pengurang, 0);
            }
        }
        $karyawanController = new KaryawanController;
        $karyawan->masa_kerja = $karyawanController->countAge($karyawan->tanggal_pengangkat);

        return view('penghasilan.gajipajak', [
            'gj' => $gj,
            'jamsostek' => $jamsostek,
            'tunjangan' => $tk,
            'penghasilan' => $ptt,
            'bonus' => $bonus,
            'tahun' => $tahun,
            'karyawan' => $karyawan,
            'request' => $request,
            'mode' => $mode,
            'pengurang' => array_sum($pengurang),
            'pph' => $pph_yang_dilunasi
        ]);
    }

    public function import() {
        if (!auth()->user()->can('penghasilan - import - penghasilan tidak teratur - import')) {
            return view('roles.forbidden');
        }
        return view('penghasilan.import');
    }

    public function insertPenghasilan(Request $request) {
        try{
            DB::beginTransaction();
            $bulan = intval(date('m', strtotime($request->tanggal)));
            $tahun = date('Y', strtotime($request->tanggal));

            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';

            DB::table('penghasilan_tidak_teratur')
                ->insert([
                    'nip' => $request->nip,
                    'id_tunjangan' => $request->id_tunjangan,
                    'nominal' => str_replace('.', '', $request->nominal),
                    'kd_entitas' => $kd_entitas,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'keterangan' => $request->keterangan,
                    'created_at' => $request->tanggal
                ]);
            DB::commit();
            
            if($bulan == 12  && Carbon::now()->format('d') > 25){
                DB::beginTransaction();
                $gajiPerBulanController = new GajiPerBulanController;
                $pphTerutang = $gajiPerBulanController->storePPHDesember($request->nip, $tahun, $bulan);
                $test = PPHModel::where('nip', $request->nip)
                    ->where('tahun', $tahun)
                    ->where('bulan', 12)
                    ->update([
                        'total_pph' => $pphTerutang,
                        'updated_at' => null
                    ]);

                DB::commit();
            }
            Alert::success('Berhasil', 'Berhasil menambahkan data.');
            return redirect()->route('penghasilan-tidak-teratur.index');
        } catch(Exception $e){
            DB::rollBack();
            return $e;
            Alert::error('Gagal', 'Terjadi kesalahan.'.$e->getMessage());
            return redirect()->back();
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan.'.$e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('penghasilan - pajak penghasilan')) {
            return view('roles.forbidden');
        }
        return view('penghasilan.index');
    }

    public function lists(Request $request)
    {
        if (!auth()->user()->can('penghasilan - import - penghasilan tidak teratur')) {
            return view('roles.forbidden');
        }

        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $search = $request->get('q');

        $penghasilanRepo = new PenghasilanTidakTeraturRepository();
        $data = $penghasilanRepo->getPenghasilan($search, $limit, $page);
        return view('penghasilan.index-list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('penghasilan - import - penghasilan tidak teratur - import')) {
            return view('roles.forbidden');
        }
        $data = TunjanganModel::where('kategori', 'tidak teratur')->get();

        return view('penghasilan.add', compact('data'));
    }

    public function createTidakTeratur()
    {
        if (!auth()->user()->can('penghasilan - import - penghasilan tidak teratur - import')) {
            return view('roles.forbidden');
        }
        $data = TunjanganModel::where('kategori', 'tidak teratur')->get();
        return view('penghasilan.add-input-tidak-teratur', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('penghasilan - import - penghasilan tidak teratur - import')) {
            return view('roles.forbidden');
        }
        $request->validate([
            'tanggal' => 'required',
            'nip' => 'required',
            'nominal' => 'required',
        ], [
            'required' => 'Data harus diisi.'
        ]);
        DB::beginTransaction();
        try{
            $tanggalVal = GajiPerBulanModel::where('bulan', Carbon::parse($request->get('tanggal'))->format('m'))
                ->where('tahun', Carbon::parse($request->get('tanggal'))->format('Y'))
                ->first();
            if($tanggalVal != null){
                if(Carbon::parse($tanggalVal->created_at) > Carbon::parse($request->get('tanggal')) && Carbon::parse($request->get('tanggal'))->format('d') <= 25 && Carbon::parse($request->get('tanggal'))->format('m') == Carbon::parse($tanggalVal->created_at)->format('m')){
                    Alert::error('Terjadi keselahan', 'Proses import tidak dapat dilakukan karena gaji bulan ini telah diproses.');
                    return redirect()->back();
                }
            }
            $nip = explode(',', $request->get('nip'));
            $nominal = explode(',', $request->get('nominal'));
            // $kdEntitas = explode(',', $request->get('kd_entitas'));
            $keterangan = [];
            if(strlen($request->get('keterangan')) > 0){
                $keterangan = explode(',', $request->get('keterangan'));
            }
            $inserted = array();
            $tunjangan = $request->get('kategori');
            // if($tunjangan == 'spd'){
            //     $tunjangan = $request->get('kategori_spd');
            // }
            $idTunjangan = TunjanganModel::where('nama_tunjangan', 'like', "%$tunjangan%")->first();

            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';

            foreach($nip as $key => $item){
                array_push($inserted, [
                    'nip' => $item,
                    'id_tunjangan' => $idTunjangan->id,
                    'bulan' => (int) Carbon::parse($request->get('tanggal'))->format('m'),
                    'tahun' => (int) Carbon::parse($request->get('tanggal'))->format('Y'),
                    'nominal' => str_replace('.', '', $nominal[$key]),
                    'kd_entitas' => $kd_entitas,
                    'keterangan' => count($keterangan) > 0 ? $keterangan[$key] : null,
                    'created_at' => $request->get('tanggal')
                ]);
            }

            ImportPenghasilanTidakTeraturModel::insert($inserted);
            DB::commit();

            if(Carbon::parse($request->get('tanggal'))->format('m') == 12 && Carbon::now()->format('d') > 25){
                $gajiPerBulanController = new GajiPerBulanController;
                foreach($nip as $key => $item){
                    DB::beginTransaction();
                    $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($request->get('tanggal'))->format('Y'), Carbon::parse($request->get('tanggal'))->format('m'));
                    PPHModel::where('nip', $request->nip)
                        ->where('tahun', Carbon::parse($request->get('tanggal'))->format('Y'))
                        ->where('bulan', 12)
                        ->update([
                            'total_pph' => $pphTerutang,
                            'updated_at' => null
                        ]);
                    DB::commit();
                }
            }

            Alert::success('Berhasil', 'Berhasil menambahkan data penghasilan');
            return redirect()->route('penghasilan-tidak-teratur.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('pajak_penghasilan.create');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('pajak_penghasilan.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (!auth()->user()->can('penghasilan - import - penghasilan tidak teratur - detail')) {
            return view('roles.forbidden');
        }
        try{
            $idTunjangan = $request->get('idTunjangan');
            $tanggal = $request->get('tanggal');
            $limit = $request->has('page_length') ? $request->get('page_length') : 10;
            $page = $request->has('page') ? $request->get('page') : 1;
            $search = $request->get('q');

            $repo = new PenghasilanTidakTeraturRepository();
            $data = $repo->getAllPenghasilan($search, $limit, $page, $tanggal, $idTunjangan);
            $tunjangan = $data[0]->nama_tunjangan;
            return view('penghasilan.detail', compact(['data','tunjangan']));
        } catch(Exception $e){
            Alert::error('Gagal!', 'Terjadi kesalahan. ' . $e->getMessage());
            return back();
        } catch(QueryException $e){
            Alert::error('Gagal!', 'Terjadi kesalahan. ' . $e->getMessage());
            return back();
        }
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

    public function lock(Request $request)
    {
        if (!auth()->user()->can('penghasilan - lock - penghasilan tidak teratur')) {
            return view('roles.forbidden');
        }
        $repo = new PenghasilanTidakTeraturRepository;
        $body = [
            'id_tunjangan' => $request->get('id_tunjangan'),
            'tanggal' => $request->get('tanggal'),
        ];
        $repo->lock($body);
        Alert::success('Berhasil lock tunjangan.');
        return redirect()->route('penghasilan-tidak-teratur.index');
    }
    public function unlock(Request $request)
    {
        if (!auth()->user()->can('penghasilan - unlock - penghasilan tidak teratur')) {
            return view('roles.forbidden');
        }
        $repo = new PenghasilanTidakTeraturRepository;
        $body = [
            'id_tunjangan' => $request->get('id_tunjangan'),
            'tanggal' => $request->get('tanggal'),
        ];
        $repo->unlock($body);
        Alert::success('Berhasil unlock tunjangan.');
        return redirect()->route('penghasilan-tidak-teratur.index');
    }

    public function editTunjangan($idTunjangan, $tanggal)
    {
        if (!auth()->user()->can('penghasilan - edit - penghasilan tidak teratur')) {
            return view('roles.forbidden');
        }
        $id = $idTunjangan;
        $repo = new PenghasilanTidakTeraturRepository;
        $penghasilan = $repo->TunjanganSelected($id);
        return view('penghasilan.edit', [
            'penghasilan' => $penghasilan,
            'old_id' => $id,
            'old_created_at' => $tanggal
        ]);
    }

    public function editTunjanganPost(Request $request)
    {
        if (!auth()->user()->can('penghasilan - edit - penghasilan tidak teratur')) {
            return view('roles.forbidden');
        }
        $request->validate([
            'tanggal' => 'required',
            'nip' => 'required',
            'nominal' => 'required',
        ], [
            'required' => 'Data harus diisi.'
        ]);
        DB::beginTransaction();
        try {
            $nip = explode(',', $request->get('nip'));
            $nominal = explode(',', $request->get('nominal'));
            // $kdEntitas = explode(',', $request->get('kd_entitas'));
            $keterangan = [];
            if (strlen($request->get('keterangan')) > 0) {
                $keterangan = explode(',', $request->get('keterangan'));
            }
            $inserted = array();
            $tunjangan = $request->get('kategori');
            if ($tunjangan == 'spd') {
                $tunjangan = $request->get('kategori_spd');
            }
            $idTunjangan = TunjanganModel::where('nama_tunjangan', 'like', "%$tunjangan%")->first();

            $old_tunjangan = $request->get('old_tunjangan');
            $old_tanggal = $request->get('old_tanggal');

            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';

            DB::table('penghasilan_tidak_teratur')
            ->where('id_tunjangan', $old_tunjangan)
            ->where(DB::raw('DATE(created_at)'), $old_tanggal)
            ->delete();
            foreach ($nip as $key => $item) {
                array_push($inserted, [
                    'nip' => $item,
                    'id_tunjangan' => $old_tunjangan,
                    'bulan' => (int) Carbon::parse($request->get('tanggal'))->format('m'),
                    'tahun' => (int) Carbon::parse($request->get('tanggal'))->format('Y'),
                    'nominal' => str_replace('.', '', $nominal[$key]),
                    'kd_entitas' => $kd_entitas,
                    'keterangan' => count($keterangan) > 0 ? $keterangan[$key] : null,
                    'created_at' => $request->get('tanggal')
                ]);
            }

            ImportPenghasilanTidakTeraturModel::insert($inserted);
            DB::commit();

            Alert::success('Berhasil', 'Berhasil menambahkan data penghasilan');
            return redirect()->route('penghasilan-tidak-teratur.index');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('pajak_penghasilan.create');
        } catch (QueryException $e) {
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('pajak_penghasilan.create');
        }
    }

    function templateTidakTeratur() {
        $filename = Carbon::now()->format('his').'-penghasilan_tidak_teratur'.'.'.'xlsx';
        return Excel::download(new ExportBiayaTidakTeratur,$filename);
    }

    function templateBiayaKesehatan() {
        $filename = Carbon::now()->format('his').'-penghasilan_tidak_teratur_biaya_kesehatan'.'.'.'xlsx';
        return Excel::download(new ExportBiayaKesehatan,$filename);
    }

    function templateBiayaDuka() {
        $filename = Carbon::now()->format('his').'-penghasilan_tidak_teratur_uang_duka'.'.'.'xlsx';
        return Excel::download(new ExportBiayaDuka,$filename);
    }
}
