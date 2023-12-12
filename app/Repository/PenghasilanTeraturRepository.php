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
        $data = DB::table('transaksi_tunjangan')
                ->select(
                    'transaksi_tunjangan.nip as nip_tunjangan',
                    'transaksi_tunjangan.id_tunjangan as id_transaksi_tunjangan',
                    'transaksi_tunjangan.nominal',
                    'mst_karyawan.nama_karyawan',
                    'mst_tunjangan.nama_tunjangan',
                    'transaksi_tunjangan.is_lock',
                    DB::raw('DATE(transaksi_tunjangan.tanggal) as tanggal'),
                    DB::raw('SUM(transaksi_tunjangan.nominal) as total_nominal'),
                    DB::raw('COUNT(transaksi_tunjangan.id) as total_data'),
                )
                ->join('mst_karyawan', 'mst_karyawan.nip', 'transaksi_tunjangan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', 'transaksi_tunjangan.id_tunjangan')
                ->where('mst_tunjangan.kategori', 'teratur')
                ->where('mst_tunjangan.is_import', 1)
                ->where(function ($query) use ($search) {
                    $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                    ->orWhere('transaksi_tunjangan.nominal', 'like', "%$search%")
                    ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%");
                })
                    ->groupBy('transaksi_tunjangan.id_tunjangan', 'tanggal')
                    ->orderBy('transaksi_tunjangan.tanggal')
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
        $data = DB::table('transaksi_tunjangan')
                ->select(
                    'transaksi_tunjangan.nip as nip_tunjangan',
                    'transaksi_tunjangan.id_tunjangan as id_transaksi_tunjangan',
                    'transaksi_tunjangan.nominal',
                    'transaksi_tunjangan.tanggal',
                    'mst_karyawan.nama_karyawan',
                    'mst_tunjangan.nama_tunjangan'
                )
                    ->join('mst_karyawan', 'mst_karyawan.nip', 'transaksi_tunjangan.nip')
                    ->join('mst_tunjangan', 'mst_tunjangan.id', 'transaksi_tunjangan.id_tunjangan')
                    ->where('mst_tunjangan.kategori', 'teratur')
                    ->where('mst_tunjangan.is_import', 1)
                    ->where(function ($query) use ($search) {
                        $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                            ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                            ->orWhere('transaksi_tunjangan.nominal', 'like', "%$search%")
                            ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%");
                    })
                    ->where('transaksi_tunjangan.id_tunjangan', $idTunjangan)
                    ->where(DB::raw('DATE(transaksi_tunjangan.tanggal)'), $createdAt)
                    ->paginate($limit);

        return $data;
    }

    public function getNamaTunjangan($idTunjangan){

        $tunjangan = TunjanganModel::find($idTunjangan);

        return $tunjangan;
    }

    public function excelVitamin($bulan, $tahun){
        $cabang = CabangModel::select('kd_cabang')->pluck('kd_cabang');
        $data = DB::table('transaksi_tunjangan')->select(
            'transaksi_tunjangan.nip',
            'k.nama_karyawan',
            'k.no_rekening',
            'transaksi_tunjangan.nominal as vitamin'
        )
            ->join('mst_karyawan as k', 'k.nip', 'transaksi_tunjangan.nip')
            ->where('id_tunjangan', 13) //vitamin
            ->whereMonth('transaksi_tunjangan.tanggal', $bulan)
            ->whereYear('transaksi_tunjangan.tanggal', $tahun)
            ->get();

        return $data;
    }

    public function lock(array $data){
        $idTunjangan = $data['id_tunjangan'];
        $createdAt = $data['tanggal'];
        return DB::table('transaksi_tunjangan')->where('id_tunjangan', $idTunjangan)
        ->where(DB::raw('DATE(transaksi_tunjangan.tanggal)'), $createdAt)->update([
            'is_lock' => 1
        ]);
    }
    public function unlock(array $data){
        $idTunjangan = $data['id_tunjangan'];
        $createdAt = $data['tanggal'];
        return DB::table('transaksi_tunjangan')->where('id_tunjangan', $idTunjangan)
        ->where(DB::raw('DATE(transaksi_tunjangan.tanggal)'), $createdAt)->update([
            'is_lock' => 0
        ]);
    }

    public function TunjanganSelected($id){
        $data = TunjanganModel::find($id);
        return $data;
    }
}

?>
