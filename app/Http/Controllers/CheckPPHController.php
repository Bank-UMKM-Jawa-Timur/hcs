<?php

namespace App\Http\Controllers;

use App\Helpers\CheckHitungPPH;
use App\Models\KaryawanModel;
use Illuminate\Http\Request;

class CheckPPHController extends Controller
{
    public function index(Request $request) {
        $orderRaw = "
            CASE
            WHEN mst_karyawan.kd_jabatan='DIRUT' THEN 1
            WHEN mst_karyawan.kd_jabatan='DIRUMK' THEN 2
            WHEN mst_karyawan.kd_jabatan='DIRPEM' THEN 3
            WHEN mst_karyawan.kd_jabatan='DIRHAN' THEN 4
            WHEN mst_karyawan.kd_jabatan='KOMU' THEN 5
            WHEN mst_karyawan.kd_jabatan='KOM' THEN 7
            WHEN mst_karyawan.kd_jabatan='STAD' THEN 8
            WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 9
            WHEN mst_karyawan.kd_jabatan='PSD' THEN 10
            WHEN mst_karyawan.kd_jabatan='PC' THEN 11
            WHEN mst_karyawan.kd_jabatan='PBP' THEN 12
            WHEN mst_karyawan.kd_jabatan='PBO' THEN 13
            WHEN mst_karyawan.kd_jabatan='PEN' THEN 14
            WHEN mst_karyawan.kd_jabatan='ST' THEN 15
            WHEN mst_karyawan.kd_jabatan='NST' THEN 16
            WHEN mst_karyawan.kd_jabatan='IKJP' THEN 17 END ASC
        ";
        $tanggal = date('Y-m-d', strtotime("2024-01-25"));
        $bulan = (int) date('m', strtotime($tanggal));
        $tahun = (int) date('Y', strtotime($tanggal));
        $kd_entitas = $request->get('kd_entitas');
        $kd_cabang = \DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();
        $cabang = \DB::table('mst_cabang')
                        ->select('kd_cabang', 'nama_cabang')
                        ->orderBy('kd_cabang')
                        ->get();

        if (auth()->user()->hasRole('cabang')) {
            $karyawan = KaryawanModel::whereNull('tanggal_penonaktifan')
                ->where('kd_entitas', auth()->user()->kd_cabang)
                ->orderByRaw($orderRaw)
                ->get();
        } else {
            if ($kd_entitas == '000') {
                $karyawan = KaryawanModel::whereNull('tanggal_penonaktifan')
                                        ->where(function($query) use ($kd_cabang) {
                                            $query->whereNotIn('kd_entitas', $kd_cabang)
                                                ->orWhereNull('kd_entitas');
                                        })
                                        ->orderByRaw($orderRaw)
                                        ->get();
            }
            else {
                $karyawan = KaryawanModel::whereNull('tanggal_penonaktifan')
                                        ->where('kd_entitas', $kd_entitas)
                                        ->orderByRaw($orderRaw)
                                        ->get();
            }
        }
        $result = [];
        foreach ($karyawan as $key => $value) {
            $data = CheckHitungPPH::checkPPH58($tanggal, $bulan, $tahun, $value);
            array_push($result, $data);
        }
        // return $result;

        return view('cek-pph', compact('cabang', 'result'));
    }
}
