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
}