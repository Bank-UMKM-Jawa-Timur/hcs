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
            'p.kredit_koperasi',
            'p.iuran_koperasi',
            'p.kredit_pegawai',
            'p.iuran_ik',
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
            ->orderBy('p.id', 'ASC')
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
        $karyawan = KaryawanModel::select(
            'mst_karyawan.nip',
        )
            ->whereNull('tanggal_penonaktifan')
            ->get();

        return $karyawan;
    }


}
