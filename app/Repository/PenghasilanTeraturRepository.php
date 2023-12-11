<?php

namespace App\Repository;

use App\Models\CabangModel;
use App\Models\GajiPerBulanModel;
use App\Models\TunjanganModel;
use Illuminate\Support\Facades\DB;

class PenghasilanTeraturRepository
{
    // nip, nama karyawan, nama tunjangan, nominal, tahun, bulan
    // search semua
    public function getPenghasilanTeraturImport($search, $limit=10, $page=1 ) {
        $data = DB::table('tunjangan_karyawan')
                ->select(
                    'tunjangan_karyawan.nip as nip_tunjangan',
                    'tunjangan_karyawan.id_tunjangan as id_tunjangan_karyawan',
                    'tunjangan_karyawan.nominal',
                    'tunjangan_karyawan.created_at',
                    'mst_karyawan.nama_karyawan',
                    'mst_tunjangan.nama_tunjangan',
                    DB::raw('DATE(tunjangan_karyawan.created_at) as tanggal'),
                    DB::raw('SUM(tunjangan_karyawan.nominal) as total_nominal')
                )
                ->join('mst_karyawan', 'mst_karyawan.nip', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.kategori', 'teratur')
                ->where('mst_tunjangan.is_import', 1)
                ->where(function ($query) use ($search) {
                    $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                    ->orWhere('tunjangan_karyawan.nominal', 'like', "%$search%")
                    ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%");
                })
                    ->groupBy('tunjangan_karyawan.id_tunjangan', 'tanggal')
                    ->orderBy('tanggal')
                    ->paginate($limit);
        return $data;
    }

    public function getDetailTunjangan($idTunjangan, $createdAt, $search, $limit){
        $data = DB::table('tunjangan_karyawan')
                ->select(
                    'tunjangan_karyawan.nip as nip_tunjangan',
                    'tunjangan_karyawan.id_tunjangan as id_tunjangan_karyawan',
                    'tunjangan_karyawan.nominal',
                    'tunjangan_karyawan.created_at',
                    'mst_karyawan.nama_karyawan',
                    'mst_tunjangan.nama_tunjangan'
                )
                    ->join('mst_karyawan', 'mst_karyawan.nip', 'tunjangan_karyawan.nip')
                    ->join('mst_tunjangan', 'mst_tunjangan.id', 'tunjangan_karyawan.id_tunjangan')
                    ->where('mst_tunjangan.kategori', 'teratur')
                    ->where('mst_tunjangan.is_import', 1)
                    ->where(function ($query) use ($search) {
                        $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                            ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                            ->orWhere('tunjangan_karyawan.nominal', 'like', "%$search%")
                            ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%");
                    })
                    ->where('tunjangan_karyawan.id_tunjangan', $idTunjangan)
                    ->where(DB::raw('DATE(tunjangan_karyawan.created_at)'), $createdAt)
                    ->paginate($limit);

        return $data;
    }

    public function getNamaTunjangan($idTunjangan){

        $tunjangan = TunjanganModel::find($idTunjangan);

        return $tunjangan;
    }

    public function excelVitamin($bulan, $tahun){
        $cabang = CabangModel::select('kd_cabang')->pluck('kd_cabang');
        $data = DB::table('tunjangan_karyawan')->select(
            'tunjangan_karyawan.nip',
            'k.nama_karyawan',
            'k.no_rekening',
            'tunjangan_karyawan.nominal as vitamin'
        )
            ->join('mst_karyawan as k', 'k.nip', 'tunjangan_karyawan.nip')
            ->where('id_tunjangan', 13) //vitamin
            ->whereMonth('tunjangan_karyawan.created_at', $bulan)
            ->whereYear('tunjangan_karyawan.created_at', $tahun)
            ->get();

        return $data;
    }
}

?>
