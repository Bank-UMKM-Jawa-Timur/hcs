<?php

namespace App\Repository;

use App\Service\EntityService;
use Illuminate\Support\Facades\DB;

class MutasiRepository
{
    public function get($search, $limit=10) {
        $data = DB::table('demosi_promosi_pangkat')
            ->where('keterangan', 'Mutasi')
            ->select(
                'demosi_promosi_pangkat.*',
                'karyawan.*',
                'newPos.nama_jabatan as jabatan_baru',
                'oldPos.nama_jabatan as jabatan_lama',
                DB::raw("
                    IF((cabang_lama.nama_cabang != 'NULL' AND IFNULL(div_lama.nama_divisi, '-') = '-' AND IFNULL(sub_div_lama.nama_subdivisi, '-') = '-'),
                        CONCAT('Cab.', cabang_lama.nama_cabang),
                        IF((IFNULL(div_lama.nama_divisi, '-') != '-' AND IFNULL(sub_div_lama.nama_subdivisi, '-') = '-'), CONCAT(div_lama.nama_divisi, ' (Pusat)'),
                            IF(IFNULL(sub_div_lama.nama_subdivisi, '-') != '-', CONCAT(sub_div_lama.nama_subdivisi, ' (Pusat)'), 
                            IF(IFNULL(bagian_lama.nama_bagian, '-') != '-', CONCAT(bagian_lama.nama_bagian, ' (Pusat)'), '-'))
                        )
                    ) AS kantor_lama
                "),
                DB::raw("
                    IF((cabang_baru.nama_cabang != 'NULL' AND IFNULL(div_baru.nama_divisi, '-') = '-' AND IFNULL(sub_div_baru.nama_subdivisi, '-') = '-'),
                        CONCAT('Cab.', cabang_baru.nama_cabang),
                        IF((IFNULL(div_baru.nama_divisi, '-') != '-' AND IFNULL(sub_div_baru.nama_subdivisi, '-') = '-'), CONCAT(div_baru.nama_divisi, ' (Pusat)'),
                            IF(IFNULL(sub_div_baru.nama_subdivisi, '-') != '-', CONCAT(sub_div_baru.nama_subdivisi, ' (Pusat)'), 
                            IF(IFNULL(bagian_baru.nama_bagian, '-') != '-', CONCAT(bagian_baru.nama_bagian, ' (Pusat)'), '-'))
                        )
                    ) AS kantor_baru
                ")
            )
            ->join('mst_karyawan as karyawan', 'karyawan.nip', 'demosi_promosi_pangkat.nip')
            ->join('mst_jabatan as newPos', 'newPos.kd_jabatan', 'demosi_promosi_pangkat.kd_jabatan_baru')
            ->join('mst_jabatan as oldPos', 'oldPos.kd_jabatan', 'demosi_promosi_pangkat.kd_jabatan_lama')
            // Kantor lama
            ->leftJoin('mst_divisi as div_lama', 'div_lama.kd_divisi', 'demosi_promosi_pangkat.kd_entitas_lama')
            ->leftJoin('mst_sub_divisi as sub_div_lama', 'sub_div_lama.kd_subdiv', 'demosi_promosi_pangkat.kd_entitas_lama')
            ->leftJoin('mst_cabang as cabang_lama', 'cabang_lama.kd_cabang', 'demosi_promosi_pangkat.kd_entitas_lama')
            ->leftJoin('mst_bagian as bagian_lama', 'bagian_lama.kd_bagian', 'demosi_promosi_pangkat.kd_bagian_lama')
            // Kantor Baru
            ->leftJoin('mst_divisi as div_baru', 'div_baru.kd_divisi', 'demosi_promosi_pangkat.kd_entitas_baru')
            ->leftJoin('mst_sub_divisi as sub_div_baru', 'sub_div_baru.kd_subdiv', 'demosi_promosi_pangkat.kd_entitas_baru')
            ->leftJoin('mst_cabang as cabang_baru', 'cabang_baru.kd_cabang', 'demosi_promosi_pangkat.kd_entitas_baru')
            ->leftJoin('mst_bagian as bagian_baru', 'bagian_baru.kd_bagian', 'demosi_promosi_pangkat.kd_bagian')
            ->when($search, function($query) use ($search) {
                $query->where('karyawan.nip', 'LIKE', "%$search%")
                    ->orWhere('karyawan.nama_karyawan', 'LIKE', "%$search%")
                    ->orWhereDate('demosi_promosi_pangkat.tanggal_pengesahan', $search)
                    ->orWhere('newPos.nama_jabatan',  'LIKE', "%$search%")
                    ->orWhere('oldPos.nama_jabatan',  'LIKE', "%$search%")
                    ->orWhere('div_lama.nama_divisi',  'LIKE', "%$search%")
                    ->orWhere('sub_div_lama.nama_subdivisi',  'LIKE', "%$search%")
                    ->orWhere('cabang_lama.nama_cabang',  'LIKE', "%$search%")
                    ->orWhere('div_baru.nama_divisi',  'LIKE', "%$search%")
                    ->orWhere('sub_div_baru.nama_subdivisi',  'LIKE', "%$search%")
                    ->orWhere('cabang_baru.nama_cabang',  'LIKE', "%$search%")
                    ->orWhere('demosi_promosi_pangkat.bukti_sk',  'LIKE', "%$search%");
            })
            ->orderBy('tanggal_pengesahan', 'desc')
            ->paginate($limit);

        return $data;
    }
}