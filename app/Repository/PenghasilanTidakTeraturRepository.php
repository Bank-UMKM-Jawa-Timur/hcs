<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class PenghasilanTidakTeraturRepository
{
    public function getDataBonus($search, $limit=10, $page=1) {
      $bonus = DB::table('penghasilan_tidak_teratur')
      ->join('mst_karyawan', 'penghasilan_tidak_teratur.nip', '=', 'mst_karyawan.nip')
      ->join('mst_tunjangan', 'penghasilan_tidak_teratur.id_tunjangan', '=', 'mst_tunjangan.id')
      ->select(
        'penghasilan_tidak_teratur.id',
        'penghasilan_tidak_teratur.nip',
        'mst_karyawan.nama_karyawan',
        'mst_tunjangan.nama_tunjangan',
        'nominal',
        'bulan',
        'tahun',
        'keterangan',
      )
      ->where(function ($query) use ($search) {
        $query->where('penghasilan_tidak_teratur.nip', 'like', "%$search%")
            ->orWhere('mst_karyawan.nama_karyawan', 'like', "%$search%")
            ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%")
            ->orWhere('nominal', 'like', "%$search%")
            ->orWhere('keterangan', 'like', "%$search%");
      })
      ->orderBy('id', 'ASC')
      ->paginate($limit);

      return $bonus;
    }

}
