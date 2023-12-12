<?php

namespace App\Service;

use App\Models\KaryawanModel;
use App\Models\PjsModel;
use App\Models\PengkinianKaryawanModel;
use App\Models\PengkinianPjsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntityService
{
    private static $posAbbrevs = [
        'Pemimpin Sub Divisi' => 'PSD',
        'Pemimpin Bidang Operasional' => 'PBO',
        'Pemimpin Bidang Pemasaran' => 'PBP',
    ];

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
            'div' => $div,
            'subDiv' => null
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

        if($request->kd_bagian && !$branch) return null;
        if ($division && $subdiv) return $subdiv;
        if ($division) return $division;
        if ($branch) return $branch;

        return false;
    }

    public static function getEntityFromRequestEdit(Request $request)
    {
        $division = $request->divisi;
        $subdiv = $request->subdiv;
        $branch = $request->cabang;
        // dd($request->all());

        if($request->bagian && $branch == null) return null;
        if ($division && $subdiv) return $subdiv;
        if ($division) return $division;
        if ($branch) return $branch;

        return false;
    }

    public static function getPosition(KaryawanModel|PjsModel $model)
    {
        $prefix = ($model instanceof PjsModel) ? 'Pjs. ' : '';
        if($model instanceof PjsModel){
            $prefix = 'Pjs. ';
        } else if($model instanceof KaryawanModel) {
            if($model->status_jabatan == 'Penjabat'){
                $prefix = 'Pj. ';
            } else {
                $prefix = '';
            }
        }
        $bagian = $model->bagian?->nama_bagian;
        $entitas = $model->entitas;
        $jabatan = $model->jabatan ? static::abbrevPos($model->jabatan->nama_jabatan) : '';

        if (isset($entitas->subDiv))
            return "{$prefix}{$jabatan} {$bagian} {$entitas->subDiv->nama_subdivisi}";

        if (isset($entitas->div))
            return "{$prefix}{$jabatan} {$bagian} {$entitas->div->nama_divisi}";

        return "{$prefix}$jabatan $bagian";
    }

    public static function getPositionPengkinian(PengkinianKaryawanModel|PengkinianPjsModel $model)
    {
        $prefix = ($model instanceof PjsModel) ? 'Pjs. ' : '';
        $bagian = $model->bagian?->nama_bagian;
        $entitas = $model->entitas;
        $jabatan = static::abbrevPos($model->jabatan->nama_jabatan);

        if (isset($entitas->subDiv))
            return "{$prefix}{$jabatan} {$bagian} {$entitas->subDiv->nama_subdivisi}";

        if (isset($entitas->div))
            return "{$prefix}{$jabatan} {$bagian} {$entitas->div->nama_divisi}";

        return "{$prefix}$jabatan $bagian";
    }

    public static function getFromBranch($branch)
    {
        $entity = DB::table('mst_bagian')
            ->where('kd_bagian', $branch)
            ->first();

        return $entity->kd_entitas;
    }

    public static function abbrevPos(String $name): string
    {
        return str_replace(
            array_keys(static::$posAbbrevs),
            array_values(static::$posAbbrevs),
            $name
        );
    }
}
