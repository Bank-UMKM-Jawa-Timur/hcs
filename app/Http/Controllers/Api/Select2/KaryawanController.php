<?php

namespace App\Http\Controllers\Api\Select2;

use App\Http\Controllers\Controller;
use App\Http\Resources\select2\KaryawanResource;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->q ?? $request->search;
        $karyawan = DB::table('mst_karyawan')
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->where(function(Builder $builder) use($query) {;
                $builder->orWhere('nip', 'LIKE', "%{$query}%");
                $builder->orWhere('nama_karyawan', 'LIKE', "%{$query}%");
            })
            ->where('status_karyawan', '!=', 'Nonaktif')
            ->orderBy('nama_karyawan', 'ASC')
            ->simplePaginate();

        return [
            'results' => KaryawanResource::collection($karyawan)->toArray($request),
            'pagination' => [
                'more' => !empty($karyawan->nextPageUrl())
            ]
        ];
    }
}
