<?php

namespace App\Service;

use App\Models\KaryawanModel;
use App\Models\PjsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntityService
{
    public static function getEntity($entity)
    {
        if (!$entity) return (object) [
            'type' => 1,
        ];

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

        if ($subDiv) return (object) [
            'type' => 1,
            'subDiv' => $subDiv,
            'div' => $div
        ];

        if ($div) return (object) [
            'type' => 1,
            'div' => $div
        ];

        return (object) [
            'type' => 2,
            'cab' => $cab
        ];
    }

    public static function getEntityFromRequest(Request $request)
    {
        $division = $request->kd_divisi;
        $subdiv = $request->kd_subdiv;
        $branch = $request->kd_cabang;

        if ($division && $subdiv) return $subdiv;
        if ($division) return $division;
        if ($branch) return $branch;

        return false;
    }

    public static function getPosition(KaryawanModel|PjsModel $model)
    {
        $jabatan = $model->jabatan->nama_jabatan;
        $entitas = $model->entitas;

        if (isset($entitas->subDiv))
            return "{$jabatan} {$entitas->subDiv->nama_subdivisi}";

        if (isset($entitas->div))
            return "{$jabatan} {$entitas->div->nama_divisi}";

        if (isset($entitas->cab))
            return "{$jabatan} {$entitas->cab->nama_cabang}";
    }
}
