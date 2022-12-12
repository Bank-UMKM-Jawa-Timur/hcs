<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class BagianService
{
    public static function getEntity($entity)
    {
        $subDiv = DB::table('mst_sub_divisi')
            ->select('*')
            ->where('kd_subdiv', $entity)
            ->first();

        $div = DB::table('mst_divisi')
            ->select('*')
            ->where('kd_divisi', ($subDiv) ? $subDiv->kd_divisi : $entity)
            ->first();

        $cab = DB::table('mst_cabang')
            ->select('*')
            ->where('kd_cabang', $entity)
            ->first();

        if($subDiv) return [
            'type' => 1,
            'subDiv' => $subDiv,
            'div' => $div
        ];

        if($div) return [
            'type' => 1,
            'div' => $div
        ];

        return [
            'type' => 2,
            'cab' => $cab
        ];
    }
}
