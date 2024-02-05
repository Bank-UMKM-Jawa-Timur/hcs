<?php

namespace App\Http\Controllers;

use App\Exports\ExportBiayaDuka;
use App\Exports\ExportBiayaKesehatan;
use App\Exports\ExportBiayaTidakTeratur;
use App\Helpers\HitungPPH;
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
            $data = DB::table('gaji_per_bulan')
                    ->select('gaji_per_bulan.*')
                    ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji_per_bulan.batch_id')
                    ->where('gaji_per_bulan.nip', $nip)
                    ->where('gaji_per_bulan.bulan', $i)
                    ->where('gaji_per_bulan.tahun', $tahun)
                    ->where('batch.status', 'final')
                    ->first();
            $pphTerutangSebelumnya = 0;
            $pphTerutangInsentifSebelumnya = 0;
            if($i > 1){
                $pphTerutangSebelumnyaObj = PPHModel::where('nip', $nip)
                    ->where('bulan', ($i -1))
                    ->where('tahun', $tahun)
                    ->first(['terutang', 'terutang_insentif']);
                if ($pphTerutangSebelumnyaObj) {
                    $pphTerutangSebelumnya = $pphTerutangSebelumnyaObj->terutang;
                    $pphTerutangInsentifSebelumnya = $pphTerutangSebelumnyaObj->terutang_insentif;
                }
            }

            if($data != null)
                array_push($pph_yang_dilunasi, ($pph != null) ? ($pph->total_pph + ($pphTerutangSebelumnya - $pphTerutangInsentifSebelumnya)) : 0);
            else
                array_push($pph_yang_dilunasi, 0);
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
                    ->sum('nominal');
                if($data != null){
                    $peng[$k] = ($penghasilan != null) ? $penghasilan : 0;
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
                    ->sum('nominal');

                if($data != null){
                    $bon[$l] = ($bns != null) ? $bns : 0;
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
                if($karyawan->status_karyawan == 'IKJP' || $karyawan->status_karyawan == 'Kontrak Perpanjangan') {
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
        $bonus_title = DB::table('mst_tunjangan')
                        ->where('kategori', 'bonus')
                        ->orderBy('id')
                        ->pluck('nama_tunjangan');

        return view('penghasilan.gajipajak', [
            'gj' => $gj,
            'jamsostek' => $jamsostek,
            'tunjangan' => $tk,
            'penghasilan' => $ptt,
            'bonus' => $bonus,
            'bonus_title' => $bonus_title,
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
            $batch = DB::table('gaji_per_bulan AS gaji')
                            ->select(
                                'gaji.nip',
                                'batch.*',
                            )
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                            ->where('gaji.nip', $request->nip)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->first();

            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
            $nominal = (int) str_replace('.', '', $request->nominal);
            $now = now();

            DB::table('penghasilan_tidak_teratur')
                ->insert([
                    'nip' => $request->nip,
                    'id_tunjangan' => $request->id_tunjangan,
                    'nominal' => $nominal,
                    'kd_entitas' => $kd_entitas,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'keterangan' => $request->keterangan,
                    'created_at' => $request->tanggal
                ]);
            // save pajak insentif
            if ($request->id_tunjangan == 31) {
                // Insentif kredit
                $pajak = HitungPPH::getPajakInsentif($request->nip, $bulan, $tahun, $nominal, 'kredit');
                $pajak_awal = $pajak;
                $current = PPHModel::where('nip', $request->nip)
                            ->where('tahun', $tahun)
                            ->where('bulan', $bulan)
                            ->first(['insentif_kredit', 'terutang_insentif']);
                $current_nominal = 0;
                $current_terutang = 0;
                if ($current) {
                    $current_nominal = $current->insentif_kredit;
                    $current_terutang = $current->terutang_insentif;
                }
                $pajak += $current_nominal;
                $pajak_awal += $current_terutang;

                $full_month = false;
                if ($batch)  {
                    $full_month = date('Y-m-d', strtotime($request->tanggal)) > $batch->tanggal_input;
                }

                if ($full_month) {
                    $update_obj = [
                        'terutang_insentif' => $pajak_awal,
                        'updated_at' => $now
                    ];
                }
                else {
                    $update_obj = [
                        'insentif_kredit' => $pajak,
                        'updated_at' => $now
                    ];
                }

                PPHModel::where('nip', $request->nip)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->update($update_obj);
            }
            if ($request->id_tunjangan == 32) {
                // Insentif penagihan
                $pajak = HitungPPH::getPajakInsentif($request->nip, $bulan, $tahun, $nominal, 'penagihan');
                $pajak_awal = $pajak;
                $current = PPHModel::where('nip', $request->nip)
                            ->where('tahun', $tahun)
                            ->where('bulan', $bulan)
                            ->first(['insentif_penagihan', 'terutang_insentif']);
                $current_nominal = 0;
                $current_terutang = 0;
                if ($current) {
                    $current_nominal = $current->insentif_penagihan;
                    $current_terutang = $current->terutang_insentif;
                }
                $pajak += $current_nominal;
                $pajak_awal += $current_terutang;

                $full_month = false;
                if ($batch)  {
                    $full_month = date('Y-m-d', strtotime($request->tanggal)) > $batch->tanggal_input;
                }

                if ($full_month) {
                    $update_obj = [
                        'terutang_insentif' => $pajak_awal,
                        'updated_at' => $now
                    ];
                }
                else {
                    $update_obj = [
                        'insentif_penagihan' => $pajak,
                        'updated_at' => $now
                    ];
                }

                PPHModel::where('nip', $request->nip)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->update($update_obj);
            }
            DB::commit();

            if($bulan == 12  && Carbon::now()->format('d') > 25){
                DB::beginTransaction();
                $gajiPerBulanController = new GajiPerBulanController;
                $pphTerutang = $gajiPerBulanController->storePPHDesember($request->nip, $tahun, $bulan);
                PPHModel::where('nip', $request->nip)
                    ->where('tahun', $tahun)
                    ->where('bulan', 12)
                    ->update([
                        'total_pph' => $pphTerutang,
                        'updated_at' => null
                    ]);

                DB::commit();
            }
            DB::beginTransaction();
            $karyawan = DB::table('mst_karyawan')
                            ->where('nip', $request->nip)
                            ->whereNull('tanggal_penonaktifan')
                            ->first();
            $pph_baru = HitungPPH::getNewPPH58($request->tanggal, (int) $bulan, $tahun, $karyawan);
            DB::commit();

            Alert::success('Berhasil', 'Berhasil menambahkan data.');
            return redirect()->route('penghasilan-tidak-teratur.index');
        } catch(Exception $e){
            DB::rollBack();
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

    public function validasiInsert(Request $request){
        $nip = $request->get('nip');
        $tanggal = $request->get('tanggal');
        $dataReady = DB::table('gaji_per_bulan as gaji')
        ->join('batch_gaji_per_bulan as batch', 'gaji.batch_id', 'batch.id')
        ->where('batch.status', 'final')
        ->where('gaji.nip', $nip)
        ->orderByDesc('batch.tanggal_input')
        ->first();

        // return ['tangal' => $tanggal, 'data' => $dataReady->tanggal_input];

        $res = [];
        if ($dataReady) {
            if ($tanggal < $dataReady->tanggal_input) {
                $res = [
                    'kode' => 1,
                    'status' => 'succses',
                    'message' => 'Tidak bisa memilih tanggal ' . $tanggal. ', karena sudah melakukan finalisasi.',
                    'data' => $dataReady
                ];
            }
            else{
                $res = [
                    'kode' => 2,
                    'status' => 'succses',
                    'message' => 'Bisa memilih tanggal ini.',
                    'data' => $dataReady
                ];
            }
        } else {
            $res = [
                'kode' => 3,
                'status' => 'succses',
                'message' => Null,
                'data' => null
            ];
        }



        return response()->json($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
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
            $inserted = array();
            $tunjangan = $request->get('kategori');

            $idTunjangan = TunjanganModel::where('nama_tunjangan', 'like', "%$tunjangan%")->first();

            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
            $now = now();
            foreach($nip as $key => $item){
                $bulan = (int) Carbon::parse($request->get('tanggal'))->format('m');
                $tahun = (int) Carbon::parse($request->get('tanggal'))->format('Y');
                $tanggal = $request->get('tanggal');
                $batch = DB::table('gaji_per_bulan AS gaji')
                            ->select(
                                'gaji.nip',
                                'batch.*',
                            )
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                            ->where('gaji.nip', $item)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->first();
                $nominal_item = (int) str_replace('.', '', $nominal[$key]);
                array_push($inserted, [
                    'nip' => $item,
                    'id_tunjangan' => $idTunjangan->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nominal' => $nominal_item,
                    'kd_entitas' => $kd_entitas,
                    'keterangan' => count($keterangan) > 0 ? $keterangan[$key] : null,
                    'created_at' => $request->get('tanggal')
                ]);

                if ($idTunjangan->id == 31) {
                    // Insentif kredit
                    $pajak = HitungPPH::getPajakInsentif($item, $bulan, $tahun, $nominal_item, 'kredit');
                    $pajak_awal = $pajak;
                    $current = PPHModel::where('nip', $item)
                                ->where('tahun', $tahun)
                                ->where('bulan', $bulan)
                                ->first(['insentif_kredit', 'terutang_insentif']);
                    $current_nominal = 0;
                    $current_terutang = 0;
                    if ($current) {
                        $current_nominal = $current->insentif_kredit;
                        $current_terutang = $current->terutang_insentif;
                    }
                    $pajak += $current_nominal;
                    $pajak_awal += $current_terutang;

                    $full_month = false;
                    if ($batch)  {
                        $full_month = $tanggal > $batch->tanggal_input;
                    }

                    if ($full_month) {
                        $update_obj = [
                            'terutang_insentif' => $pajak_awal,
                            'updated_at' => $now
                        ];
                    }
                    else {
                        $update_obj = [
                            'insentif_kredit' => $pajak,
                            'updated_at' => $now
                        ];
                    }
                    PPHModel::where('nip', $item)
                        ->where('tahun', $tahun)
                        ->where('bulan', $bulan)
                        ->update($update_obj);
                }
                if ($idTunjangan->id == 32) {
                    // Insentif penagihan
                    $pajak = HitungPPH::getPajakInsentif($item, $bulan, $tahun, $nominal_item, 'penagihan');
                    $pajak_awal = $pajak;
                    $current = PPHModel::where('nip', $item)
                                ->where('tahun', $tahun)
                                ->where('bulan', $bulan)
                                ->first(['insentif_penagihan', 'terutang_insentif']);
                    $current_nominal = 0;
                    $current_terutang = 0;
                    if ($current) {
                        $current_nominal = $current->insentif_penagihan;
                        $current_terutang = $current->terutang_insentif;
                    }
                    $pajak += $current_nominal;
                    $pajak_awal += $current_terutang;

                    if ($full_month) {
                        $update_obj = [
                            'terutang_insentif' => $pajak_awal,
                            'updated_at' => $now
                        ];
                    }
                    else {
                        $update_obj = [
                            'insentif_penagihan' => $pajak,
                            'updated_at' => $now
                        ];
                    }

                    PPHModel::where('nip', $item)
                        ->where('tahun', $tahun)
                        ->where('bulan', $bulan)
                        ->update($update_obj);
                }
            }

            ImportPenghasilanTidakTeraturModel::insert($inserted);

            DB::commit();

            // Hitung pph
            DB::beginTransaction();
            foreach($nip as $key => $item){
                $tanggal = $request->get('tanggal');
                $bulan = (int) Carbon::parse($tanggal)->format('m');
                $tahun = (int) Carbon::parse($tanggal)->format('Y');
                $karyawan = DB::table('mst_karyawan')
                            ->where('nip', $item)
                            ->whereNull('tanggal_penonaktifan')
                            ->first();

                $pph_baru = HitungPPH::getNewPPH58($tanggal, (int) $bulan, $tahun, $karyawan);
            }
            DB::commit();

            if(Carbon::parse($request->get('tanggal'))->format('m') == 12 && Carbon::now()->format('d') > 25){
                $gajiPerBulanController = new GajiPerBulanController;
                foreach($nip as $key => $item){
                    DB::beginTransaction();
                    $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($request->get('tanggal'))->format('Y'), Carbon::parse($request->get('tanggal'))->format('m'));
                    PPHModel::where('nip', $item)
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
    public function show($idTunjangan,Request $request)
    {
        if (!auth()->user()->can('penghasilan - import - penghasilan tidak teratur - detail')) {
            return view('roles.forbidden');
        }
        try{
            $bulan = (int)$request->bulan;
            $createdAt = $request->createdAt;
            $limit = $request->has('page_length') ? $request->get('page_length') : 10;
            $page = $request->has('page') ? $request->get('page') : 1;
            $search = $request->get('q');
            $kd_entitas = $request->get('kd_entitas');

            $repo = new PenghasilanTidakTeraturRepository();
            $data = $repo->getAllPenghasilan($search, $limit, $page, $bulan, $createdAt, $idTunjangan, $kd_entitas);
            $tunjangan = $repo->getNameTunjangan($idTunjangan);
            $nameCabang = $repo->getNameCabang($kd_entitas);

            // dd($data);

            return view('penghasilan.detail', compact(['data','tunjangan','nameCabang']));
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
            'tahun' => $request->get('tahun'),
            'createdAt' => $request->get('createdAt'),
            'bulan' => $request->get('bulan'),
            'kdEntitas' => $request->get('kdEntitas'),
        ];
        $repo->unlock($body);
        Alert::success('Berhasil unlock tunjangan.');
        return redirect()->route('penghasilan-tidak-teratur.index');
    }

    public function editTunjangan(Request $request)
    {
        if (!auth()->user()->can('penghasilan - edit - penghasilan tidak teratur')) {
            return view('roles.forbidden');
        }
        $id = $request->get('idTunjangan');
        $tanggal = $request->get('tanggal');
        $repo = new PenghasilanTidakTeraturRepository;
        $penghasilan = $repo->TunjanganSelected($id);
        return view('penghasilan.edit-import', [
            'penghasilan' => $penghasilan,
            'old_id' => $id,
            'old_created_at' => $tanggal
        ]);
    }
    public function editsTunjangan(Request $request)
    {
        // return $request;
        if (!auth()->user()->can('penghasilan - edit - penghasilan tidak teratur')) {
            return view('roles.forbidden');
        }
        $idTunjangan = $request->idTunjangan;
        $bulan = (int)$request->bulan;
        $createdAt = $request->tanggal;
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $search = $request->get('q');
        $kd_entitas = $request->get('kdEntitas');
        $dataTunjangan = TunjanganModel::where('kategori', 'tidak teratur')->get();

        $repo = new PenghasilanTidakTeraturRepository();
        $data = $repo->getAllPenghasilanEdit($search, $limit, $page, $bulan, $createdAt, $idTunjangan, $kd_entitas);
        $tunjangan = $repo->getNameTunjangan($idTunjangan);
        $nameCabang = $repo->getNameCabang($kd_entitas);
        $tanggal = date("Y-m-d", strtotime($request->tanggal));

        // dd($data);

        return view('penghasilan.edit', compact(['data', 'tunjangan', 'nameCabang', 'dataTunjangan', 'tanggal']));
    }

    function deleteTunjangan($id_tunjangan, $bulan, $tanggal)
    {
        $query = DB::table('penghasilan_tidak_teratur')->where('id_tunjangan', $id_tunjangan)
        ->where('bulan', $bulan)
        ->whereDate('created_at', $tanggal);
        return $query;
    }

    public function editTunjanganNewPost(Request $request){
        // return $request;
        DB::beginTransaction();
        try {
            $nip = $request->has('nip') ? $request->get('nip') : null;
            $item_id = $request->has('item_id') ? $request->get('item_id') : null;
            $tanggal = $request->has('tanggal') ? $request->get('tanggal') : null;
            $kd_entitas = $request->has('kd_entitas') ? $request->get('kd_entitas') : null;
            $temp_nip = $request->has('temp_nip') ? $request->get('temp_nip')[0] : null;
            $temp_nip_array = json_decode($temp_nip, true);
            // return count($temp_nip_array);
            if (!$item_id) {
                for ($i=0; $i < count($temp_nip_array) ; $i++) {
                    $datts =DB::table('penghasilan_tidak_teratur')
                        ->where('id_tunjangan', $request->get('id_tunjangan'))
                        ->where('bulan', $request->get('bulan'))
                        ->whereDate('created_at', $tanggal)
                        ->where('kd_entitas', $kd_entitas)
                        ->where('nip', $temp_nip_array[$i])
                        ->delete();
                    DB::commit();
                }

                // Hitung pph
                DB::beginTransaction();
                foreach ($temp_nip_array as $key => $item) {
                    $bulan = (int) Carbon::parse($tanggal)->format('m');
                    $tahun = (int) Carbon::parse($tanggal)->format('Y');
                    $karyawan = DB::table('mst_karyawan')
                                    ->where('nip', $item)
                                    ->whereNull('tanggal_penonaktifan')
                                    ->first();

                    $pph_baru = HitungPPH::getNewPPH58($tanggal, (int) $bulan, $tahun, $karyawan);
                }
                DB::commit();

                DB::beginTransaction();
                if (Carbon::parse($tanggal)->format('m') == 12 && Carbon::now()->format('d') > 25) {
                    $gajiPerBulanController = new GajiPerBulanController;
                    foreach ($temp_nip_array as $key => $item) {
                        $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($tanggal)->format('Y'), Carbon::parse($tanggal)->format('m'));
                        PPHModel::where('nip', $item)
                            ->where('tahun', Carbon::parse($tanggal)->format('Y'))
                            ->where('bulan', 12)
                            ->update([
                                'total_pph' => $pphTerutang,
                                'updated_at' => now()
                            ]);
                    }
                }
                DB::commit();
                Alert::success('Success', 'Berhasil edit data penghasilan');
            }
            else {
                $itemLamaId = DB::table('penghasilan_tidak_teratur')
                                ->where('penghasilan_tidak_teratur.created_at', $tanggal)
                                ->where('id_tunjangan', $request->id_tunjangan)
                                ->where('kd_entitas', $kd_entitas)
                                ->pluck('id')
                                ->toArray();

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
                            'created_at' => $tanggal,
                            'id_tunjangan' => $request->id_tunjangan,
                            'is_lock' => 1
                        ]);
                    }
                }
                DB::commit();

                // Hitung pph
                DB::beginTransaction();
                foreach ($nip as $key => $item) {
                    $bulan = (int) Carbon::parse($tanggal)->format('m');
                    $tahun = (int) Carbon::parse($tanggal)->format('Y');
                    $karyawan = DB::table('mst_karyawan')
                                ->where('nip', $item)
                                ->whereNull('tanggal_penonaktifan')
                                ->first();

                    $pph_baru = HitungPPH::getNewPPH58($tanggal, (int) $bulan, $tahun, $karyawan);
                }
                DB::commit();

                DB::beginTransaction();
                if (Carbon::parse($tanggal)->format('m') == 12 && Carbon::now()->format('d') > 25) {
                    $gajiPerBulanController = new GajiPerBulanController;
                    foreach ($nip as $key => $item) {
                        $pphTerutang = $gajiPerBulanController->storePPHDesember($item, Carbon::parse($tanggal)->format('Y'), Carbon::parse($tanggal)->format('m'));
                        PPHModel::where('nip', $item)
                            ->where('tahun', Carbon::parse($tanggal)->format('Y'))
                            ->where('bulan', 12)
                            ->update([
                                'total_pph' => $pphTerutang,
                                'updated_at' => now()
                            ]);
                        }
                }
                DB::commit();
                Alert::success('Success', 'Berhasil edit data penghasilan');
            }


            return redirect()->route('penghasilan-tidak-teratur.index');
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

            $repo = new PenghasilanTidakTeraturRepository;
            $dataFromCabang = $repo->getCabang($request->get('kdEntitas'));
            $dataCabangCanEdit = $repo->getCabang($kd_entitas);

            if ($request->get('kdEntitas') == $kd_entitas) {
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
            }else{
                Alert::error('Terjadi Kesalahan', 'Cabang '.$dataCabangCanEdit->nama_cabang.' Tidak Bisa Edit data Penghasilan '.$dataFromCabang->nama_cabang);
                return redirect()->route('penghasilan-tidak-teratur.index');
            }

            Alert::success('Berhasil', 'Berhasil edit data penghasilan');
            return redirect()->route('penghasilan-tidak-teratur.index');
        } catch (Exception $e) {
            DB::rollBack();
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
