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
        $kd_cabang = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : 'pusat';
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);

        $data = DB::table('transaksi_tunjangan')
                ->select(
                    'transaksi_tunjangan.nip as nip_tunjangan',
                    'transaksi_tunjangan.id_tunjangan as id_transaksi_tunjangan',
                    'transaksi_tunjangan.nominal',
                    'mst_karyawan.nama_karyawan',
                    'mst_tunjangan.nama_tunjangan',
                    'transaksi_tunjangan.is_lock',
                    'transaksi_tunjangan.bulan',
                    DB::raw('DATE(transaksi_tunjangan.tanggal) as tanggal'),
                    DB::raw('SUM(transaksi_tunjangan.nominal) as total_nominal'),
                    DB::raw('COUNT(transaksi_tunjangan.id) as total_data'),
                    DB::raw("IF(mst_karyawan.kd_entitas NOT IN(SELECT kd_cabang FROM mst_cabang where kd_cabang != '000'), 'Pusat', mst_cabang.nama_cabang) as entitas"),
                    'transaksi_tunjangan.created_at',
                    'transaksi_tunjangan.kd_entitas',
                    'mst_cabang.nama_cabang',
                    'batch_gaji_per_bulan.status'
                )
                ->join('mst_karyawan', 'mst_karyawan.nip', 'transaksi_tunjangan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', 'transaksi_tunjangan.id_tunjangan')
                ->join('mst_cabang', 'mst_cabang.kd_cabang', 'transaksi_tunjangan.kd_entitas')
                ->leftJoin('gaji_per_bulan', function ($join) {
                    $join->on('transaksi_tunjangan.nip', '=', 'gaji_per_bulan.nip')
                        ->on('gaji_per_bulan.bulan', 'transaksi_tunjangan.bulan')
                        ->on('gaji_per_bulan.tahun', '=', DB::raw('YEAR(transaksi_tunjangan.tanggal)'))
                        ->orderBy('gaji_per_bulan.created_at', 'ASC');
                })
                ->leftJoin('batch_gaji_per_bulan', function ($join) {
                    $join->on('gaji_per_bulan.batch_id', '=', 'batch_gaji_per_bulan.id');
                })
                ->where('mst_tunjangan.kategori', 'teratur')
                ->where('mst_tunjangan.is_import', 1)
                ->where(function ($query) use ($search) {
                    $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                    ->orWhere('transaksi_tunjangan.nominal', 'like', "%$search%")
                    ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%")
                    ->orWhere('mst_cabang.nama_cabang', 'like', "%$search%");
                })
                ->where(function ($query) use ($kd_cabang, $kode_cabang_arr) {
                    if ($kd_cabang != 'pusat') {
                        $query->where('transaksi_tunjangan.kd_entitas', $kd_cabang);
                    }
                })
                ->whereNull('batch_gaji_per_bulan.deleted_at')
                ->groupBy('transaksi_tunjangan.id_tunjangan', 'transaksi_tunjangan.tanggal', 'transaksi_tunjangan.kd_entitas')
                ->orderBy('transaksi_tunjangan.tanggal', 'DESC')
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

    public function getDetailTunjangan($idTunjangan, $tanggal, $createdAt, $search, $limit, $kdEntitas){
        $data = DB::table('transaksi_tunjangan')
                ->select(
                    'transaksi_tunjangan.nip as nip_tunjangan',
                    'transaksi_tunjangan.id as id_tunjangan',
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
                    ->where('transaksi_tunjangan.kd_entitas', $kdEntitas)
                    ->where('transaksi_tunjangan.id_tunjangan', $idTunjangan)
                    ->where(DB::raw('DATE(transaksi_tunjangan.tanggal)'), $tanggal)
                    ->paginate($limit);

        return $data;
    }
    public function getEditTunjangan($idTunjangan, $tanggal, $createdAt, $search, $limit, $kdEntitas){
        $data = DB::table('transaksi_tunjangan')
                ->select(
                    'transaksi_tunjangan.nip as nip_tunjangan',
                    'transaksi_tunjangan.id as id_tunjangan',
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
                    ->where('transaksi_tunjangan.kd_entitas', $kdEntitas)
                    ->where('transaksi_tunjangan.id_tunjangan', $idTunjangan)
                    ->where('transaksi_tunjangan.tanggal', $tanggal)
                    // ->paginate($limit);
                    ->get();

        return $data;
    }

    public function getNamaTunjangan($idTunjangan){

        $tunjangan = TunjanganModel::find($idTunjangan);

        return $tunjangan;
    }

    public function getNameCabang($kdEntitas){
        $data = DB::table('mst_cabang')->select(
            'kd_cabang',
            'nama_cabang'
            )
        ->where('kd_cabang', $kdEntitas)
        ->first();

        return $data;
    }

    public function excelVitamin($bulan, $tahun){
        $cabang = CabangModel::select('kd_cabang')->pluck('kd_cabang');
        if (auth()->user()->kd_cabang != null) {
            $data = DB::table('transaksi_tunjangan')->select(
                'transaksi_tunjangan.nip',
                'k.nama_karyawan',
                'k.no_rekening',
                'transaksi_tunjangan.nominal as vitamin'
            )
                ->join('mst_karyawan as k', 'k.nip', 'transaksi_tunjangan.nip')
                ->where('id_tunjangan', 13) //vitamin
                ->where('k.kd_entitas', auth()->user()->kd_cabang)
                ->whereMonth('transaksi_tunjangan.tanggal', $bulan)
                ->whereYear('transaksi_tunjangan.tanggal', $tahun)
                ->get();
        }else{
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

        }

        return $data;
    }

    public function lock(array $data){
        $idTunjangan = $data['id_tunjangan'];
        $tanggal = $data['tanggal'];
        $createdAt = $data['created_at'];

        return DB::table('transaksi_tunjangan')->where('id_tunjangan', $idTunjangan)
        ->where(DB::raw('DATE(transaksi_tunjangan.tanggal)'), $tanggal)
        ->where('transaksi_tunjangan.created_at', $createdAt)
        ->update([
            'is_lock' => 1
        ]);
    }
    public function unlock(array $data){
        $idTunjangan = $data['id_tunjangan'];
        $tanggal = $data['tanggal'];
        $createdAt = $data['created_at'];

        return DB::table('transaksi_tunjangan')->where('id_tunjangan', $idTunjangan)
        ->where(DB::raw('DATE(transaksi_tunjangan.tanggal)'), $tanggal)
        ->where('transaksi_tunjangan.created_at', $createdAt)
        ->update([
            'is_lock' => 0
        ]);
    }

    public function TunjanganSelected($id){
        $data = TunjanganModel::find($id);
        return $data;
    }
}

?>
