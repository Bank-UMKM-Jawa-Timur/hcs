<?php

namespace App\Service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ClassificationService
{
    public static function getWithDivision(Builder $karyawan, $kd_entitas): Builder
    {
        $entity = EntityService::getEntity($kd_entitas);
        $isDiv = property_exists($entity, 'div');
        $isSubDiv = property_exists($entity, 'subDiv');

        if ($isSubDiv && !$isDiv) {
            $kdSubDiv = $entity->subDiv->kd_subdiv;
            return $karyawan->orWhere('kd_entitas', $kdSubDiv);
        }

        if ($isDiv) {
            $kdDiv = $entity->div->kd_divisi;
            $subDivs = DB::table('mst_sub_divisi')
                ->where('kd_divisi', $kdDiv)
                ->pluck('kd_subdiv');

            return $karyawan->orWhere('kd_entitas', $kdDiv)
                ->orWhereIn('kd_entitas', $subDivs);
        }
    }
}
