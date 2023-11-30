<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class CabangRepository
{
    public function listCabang() {
        return DB::table('mst_cabang')
                ->select('kd_cabang', 'nama_cabang')
                ->where('kd_cabang', '!=', '000')
                ->orderBy('kd_cabang')
                ->get();
    }
}