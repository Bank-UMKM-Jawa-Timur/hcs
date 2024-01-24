<?php

namespace App\Http\Controllers;

use App\Models\CabangModel;
use App\Models\DivisiModel;
use App\Models\GajiPerBulanModel;
use App\Models\KaryawanModel;
use App\Models\SpModel;
use Illuminate\Http\Request;
use App\Service\EntityService;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Need permission
        $dataCabang = CabangModel::orderBy('kd_cabang')->get();
        $karyawanByCabang = [];
        $pusat = 0;
        foreach ($dataCabang as $value) {
            $cabang = $value->nama_cabang;
            if ($value->kd_cabang === '000') {
                $dataKaryawan = KaryawanModel::whereNotIn('kd_entitas', $dataCabang->pluck('kd_cabang'))->count();
            } else {
                $dataKaryawan = KaryawanModel::where('kd_entitas', $value->kd_cabang)->count();
            }

            $dataKaryawanByCabang = [
                'pusat' => $pusat,
                'cabang' => $cabang,
                'total_karyawan' => intval($dataKaryawan)
            ];

            array_push($karyawanByCabang, $dataKaryawanByCabang);
        }
        $totalKaryawan = $karyawanByCabang;

        // chart gaji karyawan perbulan dalam setahun
        $tahunSekarang = date('Y');
        $gajiPokok = [];
        for ($i=1; $i <= 12 ; $i++) {
            $dataGajiKaryawanPerbulan = GajiPerBulanModel::where('bulan', $i)->where('tahun', $tahunSekarang)->sum('gj_pokok');

            $gajiKaryawanPerbulan = [
                'gaji' => $dataGajiKaryawanPerbulan
            ];

            array_push($gajiPokok, $gajiKaryawanPerbulan);
        }
        $dataGaji = $gajiPokok;

        // chart gaji per cabang
        $gaji = [];
        foreach ($dataCabang as $value) {
            $cabang = $value->nama_cabang;
            $dataKaryawan = KaryawanModel::where('kd_entitas', $value->kd_cabang)->sum('gj_pokok');

            $dataKaryawan = [
                'cabang' => $cabang,
                'gaji_pokok' => intval($dataKaryawan)
            ];

            array_push($gaji, $dataKaryawan);
        }
        $gajiPerCabang = $gaji;

        // data mutasi
        $bulan_ini = date('m');

        $dataMutasi = DB::table('demosi_promosi_pangkat')
        ->where('keterangan', 'Mutasi')
            ->select(
                'demosi_promosi_pangkat.*',
                'karyawan.*',
                'newPos.nama_jabatan as jabatan_baru',
                'oldPos.nama_jabatan as jabatan_lama'
            )
            ->join('mst_karyawan as karyawan', 'karyawan.nip', '=', 'demosi_promosi_pangkat.nip')
            ->join('mst_jabatan as newPos', 'newPos.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_baru')
            ->join('mst_jabatan as oldPos', 'oldPos.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_lama')
            ->orderBy('tanggal_pengesahan', 'asc')
            ->whereMonth('tanggal_pengesahan', $bulan_ini)
            ->whereYear('tanggal_pengesahan', $tahunSekarang)
            ->limit(5)
            ->get();

        $dataMutasi->map(function ($mutasi) {
            $entity = EntityService::getEntity($mutasi->kd_entitas_baru);
            $type = $entity->type;
            $mutasi->kantor_baru = '-';

            if ($type == 2) $mutasi->kantor_baru = "Cab. " . $entity->cab->nama_cabang;
            if ($type == 1) {
                if (isset($entity->subDiv)) {
                    $mutasi->kantor_baru = $entity?->subDiv?->nama_subdivisi . " (Pusat)";
                } else if (isset($entity->div)) {
                    $mutasi->kantor_baru = $entity?->div?->nama_divisi . " (Pusat)";
                }
            }

            return $mutasi;
        });

        $dataMutasi->map(function ($mutasiLama) {
            $entityLama = EntityService::getEntity($mutasiLama->kd_entitas_lama);
            $typeLama = $entityLama->type;
            $mutasiLama->kantor_lama = '-';

            if ($typeLama == 2) $mutasiLama->kantor_lama = "Cab. " . $entityLama->cab->nama_cabang;
            if ($typeLama == 1) {
                if (isset($entityLama->subDiv)) {
                    $mutasiLama->kantor_lama = $entityLama->subDiv->nama_subdivisi . " (Pusat)";
                } else if (isset($entityLama->div)) {
                    $mutasiLama->kantor_lama = $entityLama->div->nama_divisi . " (Pusat)";
                }
            }

            return $mutasiLama;
        });

        $totalDataMutasi = DB::table('demosi_promosi_pangkat')->whereMonth('tanggal_pengesahan', $bulan_ini)->whereYear('tanggal_pengesahan', $tahunSekarang)->count();

        $dataSP = SpModel::with('karyawan')
        ->whereYear('tanggal_sp', $tahunSekarang)
        ->whereMonth('tanggal_sp', $bulan_ini)
        ->orderBy('tanggal_sp', 'DESC')
        ->limit(5)
        ->get();

        $totalDataSP = SpModel::with('karyawan')
        ->whereYear('tanggal_sp', $tahunSekarang)
        ->whereMonth('tanggal_sp', $bulan_ini)
        ->orderBy('tanggal_sp', 'DESC')
        ->count();

        $tanggalAwal = date('Y-m-01');
        $hari_ini = Carbon::now();
        $bulan_ini = date('m');
        $bulanReq = ($bulan_ini < 10) ? ltrim($bulan_ini, '0') : $bulan_ini;

        $tunjangan = GajiPerBulanModel::select(
            DB::raw('round(SUM(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_perumahan + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_teller + tj_multilevel + tj_transport + tj_pulsa + tj_vitamin + uang_makan), 0) as gaji'),
            DB::raw('round(SUM(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_perumahan + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_teller + tj_multilevel), 0) as total_gaji'),
            DB::raw('round(AVG(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_perumahan + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel), 0) as rata_rata'),
            DB::raw('round(AVG(gj_pokok), 0) as gj_pokok'),
            DB::raw('round(AVG(gj_penyesuaian), 0) as gj_penyesuaian'),
            DB::raw('round(AVG(tj_keluarga), 0) as tj_keluarga'),
            DB::raw('round(AVG(tj_telepon), 0) as tj_telepon'),
            DB::raw('round(AVG(tj_jabatan), 0) as tj_jabatan'),
            DB::raw('round(AVG(tj_perumahan), 0) as tj_perumahan'),
            DB::raw('round(AVG(tj_kemahalan), 0) as tj_kemahalan'),
            DB::raw('round(AVG(tj_pelaksana), 0) as tj_pelaksana'),
            DB::raw('round(AVG(tj_kesejahteraan), 0) as tj_kesejahteraan'),
            DB::raw('round(AVG(tj_teller), 0) as tj_teller'),
            DB::raw('round(AVG(tj_multilevel), 0) as tj_multilevel'),
            DB::raw('round(AVG(tj_transport), 0) as tj_transport'),
            DB::raw('round(AVG(tj_pulsa), 0) as tj_pulsa'),
            DB::raw('round(AVG(tj_vitamin), 0) as tj_vitamin'),
            DB::raw('round(AVG(uang_makan), 0) as uang_makan'),
        )
        ->whereDate('created_at', '>=', $tanggalAwal)
        ->whereDate('created_at', '<=', $hari_ini)
        ->first();

        return view('home', compact('tunjangan','totalKaryawan', 'dataGaji', 'gajiPerCabang', 'dataMutasi', 'totalDataMutasi', 'dataSP', 'totalDataSP'));
    }

    public function perCabang(){
        $dataCabang = CabangModel::orderBy('kd_cabang')->get();
        $karyawanByCabang = [];
        $pusat = 0;

        foreach ($dataCabang as $value) {
            $cabang = $value->nama_cabang;

            if ($value->kd_cabang === '000') {
                $dataKaryawan = KaryawanModel::whereNotIn('kd_entitas', $dataCabang->pluck('kd_cabang'))->count();
            } else {
                $dataKaryawan = KaryawanModel::where('kd_entitas', $value->kd_cabang)->count();
            }

            $dataKaryawanByCabang = [
                'cabang' => $cabang,
                'kode_cabang' => $value->kd_cabang,
                'total_karyawan' => intval($dataKaryawan)
            ];

            array_push($karyawanByCabang, $dataKaryawanByCabang);
        }


        $data = $karyawanByCabang;

        return view('graph.per-cabang', compact('data'));
    }

    public function listKaryawanByCabang($kode_cabang){
        $cabang = CabangModel::where('kd_cabang', $kode_cabang)->first();

        $data = KaryawanModel::where('kd_entitas', $kode_cabang)
        ->select('mst_karyawan.nip', 'mst_karyawan.nik', 'mst_karyawan.nama_karyawan', 'c.nama_cabang as kantor', 'mst_karyawan.ket_jabatan as jabatan')
        ->join('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
        ->get();

        return view('graph.list-karyawan', compact('data', 'cabang'));
    }

    public function listKaryawanBySubDivisi($sub_divisi){
        $sub_div = $sub_divisi;

        $data = KaryawanModel::where('kd_entitas', $sub_divisi)
        ->select('mst_karyawan.nip', 'mst_karyawan.nik', 'mst_karyawan.nama_karyawan', 'c.nama_cabang as kantor', 'mst_karyawan.ket_jabatan as jabatan')
        ->join('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
        ->get();

        return view('graph.list-karyawan-by-sub-divisi', compact('data', 'sub_div'));
    }

    public function perDivisi(){
        // $datadev = DivisiModel::select('kd_divisi as kode', 'nama_divisi')->get();

        // $data = [];
        // foreach ($datadev as $key => $value) {
        //     $kode = $value->kode;
        //     $dataKaryawan = KaryawanModel::where('kd_entitas', $kode)->count();
        //     $jumlahKaryawan = [
        //         'kode' => $kode,
        //         'nama_devisi' => $value->nama_divisi,
        //         'jumlah_karyawan' => $dataKaryawan
        //     ];

        //     array_push($data, $jumlahKaryawan);
        // }

        // $datas = $data;

        $datas = DB::table('mst_karyawan as k')
        ->select(
            'd.kd_divisi as kode',
            'd.nama_divisi',
            DB::raw('count(d.kd_divisi) as jumlah_karyawan')
        )
        ->join('mst_divisi as d', 'k.kd_entitas', '=', 'd.kd_divisi')
        ->join('mst_sub_divisi as s', 'd.kd_divisi', '=', 's.kd_divisi')
        ->groupBy('d.kd_divisi', 'd.nama_divisi')
        ->get();

        // return $datas;

        return view('graph.per-devisi', compact('datas'));
    }

    public function subDivisi($kode){
        $data = DB::table('mst_karyawan as k')
        ->select(
            'd.kd_divisi as kode',
            'd.nama_divisi',
            's.kd_subdiv',
            's.nama_subdivisi',
            DB::raw('count(s.kd_subdiv) as jumlah_karyawan')
        )
        ->join('mst_divisi as d', 'k.kd_entitas', '=', 'd.kd_divisi')
        ->join('mst_sub_divisi as s', 'd.kd_divisi', '=', 's.kd_divisi')
        ->where('d.kd_divisi', $kode)
        ->groupBy('s.kd_subdiv')
        ->get();

        return view('graph.sub-devisi', compact('data'));
    }
}
