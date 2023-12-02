<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class PenghasilanTeraturRepository
{
    // nip, nama karyawan, nama tunjangan, nominal, tahun, bulan
    // search semua
    public function getPenghasilanTeraturImport($search, $limit=10, $page=1 ) {
        $data = DB::table('tunjangan_karyawan')
                ->select('tunjangan_karyawan.nip as nip_tunjangan',
                    'tunjangan_karyawan.id_tunjangan as id_tunjangan_karyawan',
                    'tunjangan_karyawan.nominal',
                    'tunjangan_karyawan.created_at',
                    'mst_karyawan.nama_karyawan',
                    'mst_tunjangan.nama_tunjangan')
                ->join('mst_karyawan','mst_karyawan.nip','tunjangan_karyawan.nip')
                ->join('mst_tunjangan','mst_tunjangan.id','tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.kategori','teratur')
                ->where('mst_tunjangan.is_import',1)
                ->where(function ($query) use ($search) {
                    $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                        ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                        ->orWhere('tunjangan_karyawan.nominal', 'like', "%$search%")
                        ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%");
                })
                ->groupBy('tunjangan_karyawan.created_at')
                ->groupBy('tunjangan_karyawan.id_tunjangan')
                ->paginate($limit);
        return $data;
    }
}

?>
