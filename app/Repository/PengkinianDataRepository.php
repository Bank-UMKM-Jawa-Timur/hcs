<?php

namespace App\Repository;

use App\Http\Controllers\Utils\PaginationController;
use App\Models\CabangModel;
use App\Service\EntityService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PengkinianDataRepository
{
    private \Illuminate\Support\Collection $cabang;

    function __construct()
    {
        $this->cabang = CabangModel::pluck('kd_cabang');
    }

    public function getData($search, $limit=1, $page=1) {
        $orderRaw = "CASE WHEN h.kd_jabatan='PIMDIV' THEN 1 WHEN h.kd_jabatan='PSD' THEN 2 WHEN h.kd_jabatan='PC' THEN 3 WHEN h.kd_jabatan='PBO' THEN 4 WHEN h.kd_jabatan='PBP' THEN 5 WHEN h.kd_jabatan='PEN' THEN 6 WHEN h.kd_jabatan='ST' THEN 7 WHEN h.kd_jabatan='IKJP' THEN 8 WHEN h.kd_jabatan='NST' THEN 9 END ASC";

        $data = DB::table('history_pengkinian_data_karyawan AS h')
                ->select(
                    'h.id',
                    'h.nip',
                    'h.nik',
                    'h.nama_karyawan',
                    'h.kd_entitas',
                    'h.kd_jabatan',
                    'h.kd_bagian',
                    'h.ket_jabatan',
                    'h.status_karyawan',
                    'j.nama_jabatan',
                    'h.status_jabatan',
                    DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = h.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang) LIMIT 1), 1, 0) AS status_kantor"),
                    'c.nama_cabang',
                )
                ->join('mst_jabatan AS j', 'j.kd_jabatan', 'h.kd_jabatan')
                ->leftJoin('mst_cabang AS c', 'c.kd_cabang', 'h.kd_entitas')
                ->when($search, function($q) use ($search) {
                    $q->where('h.nip', 'like', "%$search%")
                    ->orWhere('h.nik', 'like', "%$search%")
                    ->orWhere('h.nama_karyawan', 'like', "%$search%")
                    ->orWhere('h.kd_entitas', 'like', "%$search%")
                    ->orWhere('h.kd_jabatan', 'like', "%$search%")
                    ->orWhere('h.kd_bagian', 'like', "%$search%")
                    ->orWhere('h.ket_jabatan', 'like', "%$search%")
                    ->orWhere('h.status_karyawan', 'like', "%$search%")
                    ->orWhere('j.nama_jabatan', 'like', "%$search%")
                    ->orWhere('h.status_jabatan', 'like', "%$search%");
                })
                ->orderByRaw($orderRaw)
                ->paginate($limit);

        return $data;
    }
}
