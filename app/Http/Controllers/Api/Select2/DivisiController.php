<?php

namespace App\Http\Controllers\Api\Select2;

use App\Http\Controllers\Controller;
use App\Http\Resources\select2\DivisiResource;
use App\Http\Resources\select2\SubDivisiResource;
use App\Models\DivisiModel;
use App\Models\SubDivisiModel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DivisiController extends Controller
{
    public function divisi(Request $request) {
        $query = $request->q ?? $request->search;

        $data = DivisiModel::select('kd_divisi', 'nama_divisi')
                            ->where(function (Builder $builder) use ($query) {
                                $builder->orWhere('kd_divisi', 'LIKE', "%{$query}%");
                                $builder->orWhere('nama_divisi', 'LIKE', "%{$query}%");
                            })
                            ->orderBy('kd_divisi')
                            ->simplePaginate();
        $collection = DivisiResource::collection($data);
        return $this->response($data, $collection);
    }

    public function subDivisi($kodeDiv, Request $request) {
        $query = $request->q ?? $request->search;

        $data = SubDivisiModel::select('kd_subdiv', 'nama_subdivisi', 'kd_divisi')
                            ->where(function (Builder $builder) use ($query) {
                                $builder->orWhere('kd_subdiv', 'LIKE', "%{$query}%");
                                $builder->orWhere('nama_subdivisi', 'LIKE', "%{$query}%");
                            })
                            ->where('kd_divisi', $kodeDiv)
                            ->orderBy('kd_subdiv')
                            ->simplePaginate();

        $collection = SubDivisiResource::collection($data);
        return $this->response($data, $collection);
    }
}