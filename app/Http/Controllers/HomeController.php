<?php

namespace App\Http\Controllers;

use App\Models\CabangModel;
use App\Models\GajiPerBulanModel;
use App\Models\KaryawanModel;
use App\Models\SpModel;
use Illuminate\Http\Request;
use App\Service\EntityService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\DB;

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
        $dataCabang = CabangModel::where('kd_cabang', '!=', '000')->orderBy('kd_cabang')->get();
        // return $dataCabang;
        $karyawanByCabang = [];
        foreach ($dataCabang as $value) {
            $cabang = $value->nama_cabang;
            $dataKaryawan = KaryawanModel::where('kd_entitas', $value->kd_cabang)->count();

            $dataKaryawanByCabang = [
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

        $totalDataMutasi = DB::table('demosi_promosi_pangkat')->whereMonth('tanggal_pengesahan', $bulan_ini)->count();

        $dataSP = SpModel::with('karyawan')
        ->whereMonth('tanggal_sp', $bulan_ini)
        ->orderBy('tanggal_sp', 'DESC')
        ->limit(5)
        ->get();

        $totalDataSP = SpModel::with('karyawan')
        ->whereMonth('tanggal_sp', $bulan_ini)
        ->orderBy('tanggal_sp', 'DESC')
        ->count();

        return view('home', compact('totalKaryawan', 'dataGaji', 'gajiPerCabang', 'dataMutasi', 'totalDataMutasi', 'dataSP', 'totalDataSP'));
    }
}
