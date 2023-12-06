<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class PotonganRepository
{
    public function getPotongan($search, $limit=10, $page=1) {
      $potongan = DB::table('potongan_gaji as p')
          ->select(
            'p.id',
            'p.nip',
            'k.nama_karyawan',
            'p.kredit_koperasi',
            'p.iuran_koperasi',
            'p.kredit_pegawai',
            'p.iuran_ik',
            )
            ->join('mst_karyawan as k','p.nip','=','k.nip')
            ->where(function ($query) use ($search) {
              $query->where('p.nip', 'like', "%$search%")
                  ->orWhere('k.nama_karyawan', 'like', "%$search%")
                  ->orWhere('p.kredit_koperasi', 'like', "%$search%")
                  ->orWhere('p.iuran_koperasi', 'like', "%$search%")
                  ->orWhere('p.kredit_pegawai', 'like', "%$search%")
                  ->orWhere('p.iuran_ik', 'like', "%$search%");
          })
          ->orderBy('p.id', 'ASC')
          ->paginate($limit);

        return $potongan;
    }
    
}
