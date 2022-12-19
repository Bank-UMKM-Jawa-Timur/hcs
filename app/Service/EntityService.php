<?php

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntityService
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

    public static function getEntityFromRequest(Request $request) {
        $division = $request->kd_divisi;
        $subdiv = $request->kd_subdiv;
        $branch = $request->kd_cabang;

        if($division && $subdiv) return $subdiv;
        if($division) return $division;
        if($branch) return $branch;

        return false;
    }
}
