<?php

namespace App\Repository;

use App\Models\KaryawanModel;
use Illuminate\Support\Facades\DB;

class PayrollRepository
{
    public function get($kantor, $month, $year, $search, $page=1, $limit=10) {
        /**
         * PPH 21
         * Gaji
         * Tunjangan Tetap
         * Tunjangan Tidak Tetap
         * BPJS TK
         * BPJS Kesehatan
         * Tambahan Penghasilan
         * Potongan (JP1%, DPP 5%, Kredit Koperasi, Iuran Koperasi, Kredit Pegawai, Iuran IK)
         * Slip Gaji
         */

        /**
          * Filter
          * Kantor(Pusat/Cabang)
          * Bulan
          * Tahun
          */

        /**
         * Table
         * gaji = gaji_perbulan
         * tunjangan tetap = tunjangan_karyawan
         * tunjangan tidak tetap = penghasilan_tidak_teratur
         * bpjs tk = kpj
         * bpjs kesehatan = jkn
         */

        $data = KaryawanModel::with([
                                'gaji' => function($query) use ($month, $year) {
                                    $query->select('nip', 'gj_pokok');
                                },
                                'tunjangan' => function($query) use ($month, $year) {
                                    $query->whereMonth('tunjangan_karyawan.created_at', $month)
                                        ->whereYear('tunjangan_karyawan.created_at', $year);
                                },
                                'tunjanganTidakTetap' => function($query) use ($month, $year) {
                                    $query->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('mst_tunjangan.kategori', 'tidak teratur');
                                },
                                'bonus' => function($query) use ($month, $year) {
                                    $query->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('mst_tunjangan.kategori', 'bonus');
                                },
                            ])
                            ->select(
                                'nip',
                                'nama_karyawan',
                                'kpj AS bpjs_tk',
                                'jkn AS bpjs_kesehatan',
                            )
                            ->leftJoin('mst_cabang AS c', 'c.kd_cabang', 'kd_entitas')
                            ->where(function($query) use ($month, $year) {
                                $query->whereRelation('gaji', 'bulan', $month)
                                ->whereRelation('gaji', 'tahun', $year);
                            })
                            ->get();
        return $data;
    }
}