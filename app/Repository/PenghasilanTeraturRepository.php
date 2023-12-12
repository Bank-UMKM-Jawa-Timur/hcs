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
        $data = DB::table('tunjangan_lainnya')
                ->select(
                    'tunjangan_lainnya.nip as nip_tunjangan',
                    'tunjangan_lainnya.id_tunjangan as id_tunjangan_lainnya',
                    'tunjangan_lainnya.nominal',
                    'mst_karyawan.nama_karyawan',
                    'mst_tunjangan.nama_tunjangan',
                    'tunjangan_lainnya.is_lock',
                    DB::raw('DATE(tunjangan_lainnya.tanggal)'),
                    DB::raw('SUM(tunjangan_lainnya.nominal) as total_nominal'),
                    DB::raw('COUNT(tunjangan_lainnya.id) as total_data'),
                )
                ->join('mst_karyawan', 'mst_karyawan.nip', 'tunjangan_lainnya.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', 'tunjangan_lainnya.id_tunjangan')
                ->where('mst_tunjangan.kategori', 'teratur')
                ->where('mst_tunjangan.is_import', 1)
                ->where(function ($query) use ($search) {
                    $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                    ->orWhere('tunjangan_lainnya.nominal', 'like', "%$search%")
                    ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%");
                })
                    ->groupBy('tunjangan_lainnya.id_tunjangan', 'tanggal')
                    ->orderBy('tunjangan_lainnya.tanggal')
                    ->paginate($limit);
        foreach ($data as $key => $value) {
            $bulan = date("m", strtotime($value->tanggal));
            $bulanReq = ($bulan < 10) ? ltrim($bulan, '0') : $bulan;
            $tahun = date("Y", strtotime($value->tanggal));
            $value->gajiPerBulan = GajiPerBulanModel::where('nip', $value->nip_tunjangan)
            ->whereRaw('MONTH(bulan) = ?', [$bulanReq])
            ->whereRaw('YEAR(tahun) = ?', [$tahun])
            ->first();
        }
        return $data;
    }

    public function getDetailTunjangan($idTunjangan, $createdAt, $search, $limit){
        $data = DB::table('tunjangan_lainnya')
                ->select(
                    'tunjangan_lainnya.nip as nip_tunjangan',
                    'tunjangan_lainnya.id_tunjangan as id_tunjangan_lainnya',
                    'tunjangan_lainnya.nominal',
                    'tunjangan_lainnya.tanggal',
                    'mst_karyawan.nama_karyawan',
                    'mst_tunjangan.nama_tunjangan'
                )
                    ->join('mst_karyawan', 'mst_karyawan.nip', 'tunjangan_lainnya.nip')
                    ->join('mst_tunjangan', 'mst_tunjangan.id', 'tunjangan_lainnya.id_tunjangan')
                    ->where('mst_tunjangan.kategori', 'teratur')
                    ->where('mst_tunjangan.is_import', 1)
                    ->where(function ($query) use ($search) {
                        $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                            ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                            ->orWhere('tunjangan_lainnya.nominal', 'like', "%$search%")
                            ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%");
                    })
                    ->where('tunjangan_lainnya.id_tunjangan', $idTunjangan)
                    ->where(DB::raw('DATE(tunjangan_lainnya.tanggal)'), $createdAt)
                    ->paginate($limit);

        return $data;
    }

    public function getNamaTunjangan($idTunjangan){

        $tunjangan = TunjanganModel::find($idTunjangan);

        return $tunjangan;
    }

    public function excelVitamin($bulan, $tahun){
        $cabang = CabangModel::select('kd_cabang')->pluck('kd_cabang');
        $data = DB::table('tunjangan_lainnya')->select(
            'tunjangan_lainnya.nip',
            'k.nama_karyawan',
            'k.no_rekening',
            'tunjangan_lainnya.nominal as vitamin'
        )
            ->join('mst_karyawan as k', 'k.nip', 'tunjangan_lainnya.nip')
            ->where('id_tunjangan', 13) //vitamin
            ->whereMonth('tunjangan_lainnya.tanggal', $bulan)
            ->whereYear('tunjangan_lainnya.tanggal', $tahun)
            ->get();

        return $data;
    }

    public function lock(array $data){
        $idTunjangan = $data['id_tunjangan'];
        $createdAt = $data['tanggal'];
        return DB::table('tunjangan_lainnya')->where('id_tunjangan', $idTunjangan)
        ->where(DB::raw('DATE(tunjangan_lainnya.tanggal)'), $createdAt)->update([
            'is_lock' => 1
        ]);
    }
    public function unlock(array $data){
        $idTunjangan = $data['id_tunjangan'];
        $createdAt = $data['tanggal'];
        return DB::table('tunjangan_lainnya')->where('id_tunjangan', $idTunjangan)
        ->where(DB::raw('DATE(tunjangan_lainnya.tanggal)'), $createdAt)->update([
            'is_lock' => 0
        ]);
    }

    public function TunjanganSelected($id){
        $data = TunjanganModel::find($id);
        return $data;
    }
}

?>
