<?php

namespace App\Http\Controllers\Api\Select2;

use App\Http\Controllers\Controller;
use App\Http\Resources\select2\KaryawanResource;
use App\Models\KaryawanModel;
use App\Repository\CabangRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class KaryawanController extends Controller
{
    private String $orderRaw;

    public function __construct()
    {
        $this->orderRaw = "
            CASE 
            WHEN mst_karyawan.kd_jabatan='DIRUT' THEN 1
            WHEN mst_karyawan.kd_jabatan='DIRUMK' THEN 2
            WHEN mst_karyawan.kd_jabatan='DIRPEM' THEN 3
            WHEN mst_karyawan.kd_jabatan='DIRHAN' THEN 4
            WHEN mst_karyawan.kd_jabatan='KOMU' THEN 5
            WHEN mst_karyawan.kd_jabatan='KOM' THEN 7
            WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 8
            WHEN mst_karyawan.kd_jabatan='PSD' THEN 9
            WHEN mst_karyawan.kd_jabatan='PC' THEN 10
            WHEN mst_karyawan.kd_jabatan='PBP' THEN 11
            WHEN mst_karyawan.kd_jabatan='PBO' THEN 12
            WHEN mst_karyawan.kd_jabatan='PEN' THEN 13
            WHEN mst_karyawan.kd_jabatan='ST' THEN 14
            WHEN mst_karyawan.kd_jabatan='NST' THEN 15
            WHEN mst_karyawan.kd_jabatan='IKJP' THEN 16 END ASC
        ";
    }

    public function paginateResponse(Paginator $karyawan)
    {
        return [
            'results' => KaryawanResource::collection($karyawan),
            'pagination' => [
                'more' => !empty($karyawan->nextPageUrl())
            ]
        ];
    }

    public function karyawan(Request $request)
    {
        $query = $request->q ?? $request->search;
        $cabang = $request->has('cabang') ? $request->cabang : null;
        if (auth()->user()->hasRole('cabang')) {
            $cabang = auth()->user()->kd_cabang;
        }

        $karyawan = KaryawanModel::with('jabatan')
            ->where(function (Builder $builder) use ($query) {
                $builder->orWhere('nip', 'LIKE', "%{$query}%");
                $builder->orWhere('nama_karyawan', 'LIKE', "%{$query}%");
            })
            ->when($cabang, function($query) use($cabang) {
                $query->where('kd_entitas', $cabang);
            })
            ->whereNull('tanggal_penonaktifan')
            ->where('status_karyawan', '!=', 'Nonaktif')
            ->orderByRaw($this->orderRaw)
            ->orderBy('mst_karyawan.kd_entitas')
            ->simplePaginate();

        return $this->paginateResponse($karyawan);
    }

    public function karyawan2(Request $request)
    {
        $query = $request->q ?? $request->search;
        $cabang = $request->has('cabang') ? $request->cabang : null;
        if (auth()->user()->hasRole('cabang')) {
            $cabang = auth()->user()->kd_cabang;
        }
        $hasRole = DB::table('model_has_roles')->select('role_id','model_id')->where('role_id', 5)->get();
        $dataRole = [];
        foreach ($hasRole as $item) {
            array_push($dataRole, $item->model_id);
        }
        $karyawan = KaryawanModel::with('jabatan')
            ->where(function (Builder $builder) use ($query) {
                $builder->orWhere('nip', 'LIKE', "%{$query}%");
                $builder->orWhere('nama_karyawan', 'LIKE', "%{$query}%");
            })
            ->when($cabang, function($query) use($cabang) {
                $query->where('kd_entitas', $cabang);
            })
            ->whereNull('tanggal_penonaktifan')
            ->where('status_karyawan', '!=', 'Nonaktif')
            ->whereNotIn('nip', $dataRole)
            ->orderByRaw($this->orderRaw)
            ->orderBy('mst_karyawan.kd_entitas')
            ->simplePaginate();

        return $this->paginateResponse($karyawan);
    }

    public function karyawanJabatan(Request $request)  {
        $query = $request->q ?? $request->search;
        $kantor = $request->get('kantor');
        $cabang = $request->get('cabang');
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);
        $query = $request->q ?? $request->search;
        $karyawan = KaryawanModel::with('jabatan')
            ->when($query, function (Builder $builder) use ($query) {
                $builder->orWhere('nip', 'LIKE', "%{$query}%");
                $builder->orWhere('nama_karyawan', 'LIKE', "%{$query}%");
            })
            ->when($cabang, function($q) use ($cabang, $kode_cabang_arr) {
                if ($cabang) {
                    $q->where('kd_entitas', $cabang);
                }
                else {
                    $q->whereIn('kd_entitas', $kode_cabang_arr);
                }
            })
            ->whereNull('tanggal_penonaktifan');
            $data = $karyawan->orderByRaw($this->orderRaw)
                            ->orderBy('mst_karyawan.kd_entitas')
                            ->simplePaginate();

        return $this->response($data, KaryawanResource::collection($data));
    }

    public function listKaryawan(Request $request)
    {
        $query = $request->q ?? $request->search;
        $cabang = $request->get('cabang') != '0' ? $request->get('cabang') : '';
        $divisi = $request->get('divisi') != '0' ? $request->get('divisi') : '';
        $sub_divisi = $request->get('sub_divisi') != '0' ? $request->get('sub_divisi') : '';
        $bagian = $request->get('bagian') != '0' ? $request->get('bagian') : '';

        $karyawan = KaryawanModel::with('jabatan')
                    ->where(function (Builder $builder) use ($query) {
                        $builder->orWhere('nip', 'LIKE', "%{$query}%");
                        $builder->orWhere('nama_karyawan', 'LIKE', "%{$query}%");
                    })
                    ->when($cabang, function($query) use ($cabang) {
                        $query->where('kd_entitas', $cabang);
                    })
                    ->when($divisi, function($query) use ($divisi, $sub_divisi) {
                        if ($divisi && !$sub_divisi) {
                            $query->where('kd_entitas', $divisi);
                        }
                        if ($divisi && $sub_divisi) {
                            $query->where('kd_entitas', $sub_divisi);
                        }
                    })
                    ->when($bagian, function($query) use ($bagian) {
                        $query->where('kd_bagian', $bagian);
                    })
                    ->where(function ($query) {
                        $query->where('status_karyawan', '!=', 'Nonaktif')
                            ->whereNull('tanggal_penonaktifan');
                    })
                    ->orderByRaw($this->orderRaw)
                    ->orderBy('mst_karyawan.kd_entitas')
                    ->get();

        return $this->response($karyawan, KaryawanResource::collection($karyawan));
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
            ->whereNull('tanggal_penonaktifan')
            ->orderByRaw($this->orderRaw)
            ->orderBy('mst_karyawan.kd_entitas')
            ->simplePaginate();

        return $this->response($karyawan, KaryawanResource::collection($karyawan));
    }

    // private function response(Paginator $karyawan)
    // {
    //     return [
    //         'results' => KaryawanResource::collection($karyawan),
    //         'pagination' => [
    //             'more' => !empty($karyawan->nextPageUrl())
    //         ]
    //     ];
    // }

    function getDetail($nip)
    {
        $data = '';
        $status = '';
        $req_status = 0;
        $message = '';

        try {
            $data = KaryawanModel::select('mst_karyawan.*', 'cab.nama_cabang')
                                ->leftJoin('mst_cabang AS cab', 'cab.kd_cabang', 'mst_karyawan.kd_entitas')
                                ->with('jabatan')
                                ->where('nip', $nip)->first();
            $req_status = HttpFoundationResponse::HTTP_OK;
            $status = 'success';
            $message = 'Berhasil';
            $data = $data;
        } catch (Exception $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan : ' . $e->getMessage();
        } catch (QueryException $e) {
            $req_status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
            $status = 'failed';
            $message = 'Terjadi kesalahan pada database: ' . $e->getMessage();
        } finally {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ], $req_status);
        }
    }
}
