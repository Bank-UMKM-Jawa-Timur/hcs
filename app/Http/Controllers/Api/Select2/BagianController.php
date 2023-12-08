<?php

namespace App\Http\Controllers\Api\Select2;

use App\Http\Controllers\Controller;
use App\Http\Resources\select2\BagianResource;
use App\Models\BagianModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BagianController extends Controller
{
    public function bagian(Request $request) {
        $query = $request->q ?? $request->search;
        $kdEntitas = $request->get('kd_entitas');
        $isCabang = $request->get('is_cabang');

        $data = BagianModel::select('kd_bagian', 'kd_entitas', 'nama_bagian')
                            ->where(function (Builder $builder) use ($query) {
                                $builder->orWhere('kd_bagian', 'LIKE', "%{$query}%");
                                $builder->orWhere('nama_bagian', 'LIKE', "%{$query}%");
                            });

        if ($isCabang == 'true') {
            $data = $data->where(function($q) use($kdEntitas) {
                $q->where('kd_entitas', 2)->orWhere('kd_entitas', $kdEntitas);
            });
        }
        else {
            $data = $data->where('kd_entitas', $kdEntitas);
        }

        $data = $data->orderBy('kd_bagian')->simplePaginate();

        $collection = BagianResource::collection($data);
        
        return $this->response($data, $collection);
    }
}