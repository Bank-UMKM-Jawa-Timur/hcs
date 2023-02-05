<?php

namespace App\Http\Controllers\Api\Select2;

use App\Http\Controllers\Controller;
use App\Http\Resources\select2\KaryawanResource;
use App\Models\KaryawanModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function karyawan(Request $request)
    {
        $query = $request->q ?? $request->search;

        $karyawan = KaryawanModel::with('jabatan')
            ->where(function (Builder $builder) use ($query) {
                $builder->orWhere('nip', 'LIKE', "%{$query}%");
                $builder->orWhere('nama_karyawan', 'LIKE', "%{$query}%");
            })
            ->where('status_karyawan', '!=', 'Nonaktif')
            ->orderBy('nama_karyawan', 'ASC')
            ->simplePaginate();

        return $this->response($karyawan);
    }

    public function karyawanPjs(Request $request)
    {
        $query = $request->q ?? $request->search;

        $karyawan = KaryawanModel::with('jabatan')
            ->where(function (Builder $builder) use ($query) {
                $builder->orWhere('nip', 'LIKE', "%{$query}%");
                $builder->orWhere('nama_karyawan', 'LIKE', "%{$query}%");
            })
            ->whereNotIn('nip', function (QueryBuilder $builder) {
                $builder->select('nip')
                    ->from('pejabat_sementara')
                    ->whereNull('tanggal_berakhir');
            })
            ->where('status_karyawan', '!=', 'Nonaktif')
            ->orderBy('nama_karyawan', 'ASC')
            ->simplePaginate();

        return $this->response($karyawan);
    }

    private function response(Paginator $karyawan)
    {
        return [
            'results' => KaryawanResource::collection($karyawan),
            'pagination' => [
                'more' => !empty($karyawan->nextPageUrl())
            ]
        ];
    }
}
