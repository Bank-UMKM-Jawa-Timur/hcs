<?php

namespace App\Repository;

use App\Models\PotonganModel;
use App\Models\KaryawanModel;
use Illuminate\Support\Facades\DB;

class PotonganRepository
{
    public function getPotongan($search, $limit, $page=1) {
      $potongan = DB::table('potongan_gaji as p')
          ->select(
            'p.id',
            'p.nip',
            'p.bulan',
            'p.tahun',
            'k.nama_karyawan',
            DB::raw('COUNT(p.id) as total_data'),
            DB::raw('SUM(p.kredit_koperasi) as kredit_koperasi'),
            DB::raw('SUM(p.iuran_koperasi) as iuran_koperasi'),
            DB::raw('SUM(p.kredit_pegawai) as kredit_pegawai'),
            DB::raw('SUM(p.iuran_ik) as iuran_ik'),
            )
            ->join('mst_karyawan as k','p.nip','=','k.nip')
            ->where(function ($query) use ($search) {
              $query->where('p.nip', 'like', "%$search%")
                  ->orWhere('k.nama_karyawan', 'like', "%$search%")
                  ->orWhere('p.kredit_koperasi', 'like', "%$search%")
                  ->orWhere('p.iuran_koperasi', 'like', "%$search%")
                  ->orWhere('p.kredit_pegawai', 'like', "%$search%")
                  ->orWhere('p.iuran_ik', 'like', "%$search%");
          })
            ->groupBy('p.bulan')
            ->groupBy('p.tahun')
            ->orderBy('p.bulan')
            ->orderBy('p.tahun')
            ->paginate($limit);

        return $potongan;
    }

    public function detailPotongan($bulan, $tahun, $limit, $search){
        $data = DB::table('potongan_gaji as p')
        ->select(
            'p.id',
            'p.nip',
            'p.bulan',
            'p.tahun',
            'k.nama_karyawan',
            'p.kredit_koperasi',
            'p.iuran_koperasi',
            'p.kredit_pegawai',
            'p.iuran_ik',
        )
            ->join('mst_karyawan as k', 'p.nip', '=', 'k.nip')
            ->where(function ($query) use ($search) {
                $query->where('p.nip', 'like', "%$search%")
                ->orWhere('k.nama_karyawan', 'like', "%$search%")
                ->orWhere('p.kredit_koperasi', 'like', "%$search%")
                ->orWhere('p.iuran_koperasi', 'like', "%$search%")
                ->orWhere('p.kredit_pegawai', 'like', "%$search%")
                ->orWhere('p.iuran_ik', 'like', "%$search%");
            })
            ->where('p.bulan', $bulan)
            ->where('p.tahun', $tahun)
            ->orderBy('p.nip')
            ->paginate($limit);

        return $data;
    }

    public function store(array $data)
    {
        return PotonganModel::create([
            'nip' => $data['nip'],
            'bulan' => $data['bulan'],
            'tahun' => $data['tahun'],
            'kredit_koperasi' => str_replace(".", "", $data['kredit_koperasi']),
            'iuran_koperasi' => str_replace(".", "", $data['iuran_koperasi']),
            'kredit_pegawai' => str_replace(".","",$data['kredit_pegawai']),
            'iuran_ik' => str_replace(".","", $data['iuran_ik']),
        ]);
    }

    public function dataFileExcel()
    {
        $is_cabang = auth()->user()->hasRole('cabang');
        $is_pusat = auth()->user()->hasRole('kepegawaian');

        $kd_cabang = DB::table('mst_cabang')
                    ->select('kd_cabang')
                    ->pluck('kd_cabang')
                    ->toArray();
        $karyawan = KaryawanModel::select(
                    'mst_karyawan.nip',
                )->when($is_cabang,function($query){
                    $kd_cabang = auth()->user()->kd_cabang;
                    $query->where('kd_entitas', $kd_cabang);
                })->when($is_pusat, function($q2) use ($kd_cabang) {
                        $q2->where(function($q2) use ($kd_cabang) {
                        $q2->whereNotIn('kd_entitas', $kd_cabang)
                            ->orWhere('kd_entitas', 0);
                    });
                })
                ->whereNull('tanggal_penonaktifan')
                ->get();
        return $karyawan;
    }


}
