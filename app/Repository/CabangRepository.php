<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class CabangRepository
{
    public function get() {
        return DB::table('mst_cabang')
            ->orderBy('kd_cabang')
            ->get();
    }

    public function listCabang($pluck=false) {
        $cabang = DB::table('mst_cabang')
                ->select('kd_cabang', 'nama_cabang')
                ->where('kd_cabang', '!=', '000')
                ->orderBy('kd_cabang');
        if ($pluck) {
            $cabang = $cabang->pluck('kd_cabang')->toArray();
        }
        else {
            $cabang = $cabang->get();
        }

        return $cabang;
    }
}