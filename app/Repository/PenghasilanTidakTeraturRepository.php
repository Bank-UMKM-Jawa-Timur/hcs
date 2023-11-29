<?php

namespace App\Repository;

use App\Models\ImportPenghasilanTidakTeraturModel;
use App\Models\KaryawanModel;
use App\Models\TunjanganModel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class PenghasilanTidakTeraturRepository
{
    public function getDataBonus($search, $limit=10, $page=1) {
      $bonus = DB::table('penghasilan_tidak_teratur')
      ->join('mst_karyawan', 'penghasilan_tidak_teratur.nip', '=', 'mst_karyawan.nip')
      ->join('mst_tunjangan', 'penghasilan_tidak_teratur.id_tunjangan', '=', 'mst_tunjangan.id')
      ->select(
        'penghasilan_tidak_teratur.id',
        'penghasilan_tidak_teratur.nip',
        'mst_karyawan.nama_karyawan',
        'mst_tunjangan.nama_tunjangan',
        'nominal',
        'bulan',
        'tahun',
        'keterangan',
      )
      ->where(function ($query) use ($search) {
        $query->where('penghasilan_tidak_teratur.nip', 'like', "%$search%")
            ->orWhere('mst_karyawan.nama_karyawan', 'like', "%$search%")
            ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%")
            ->orWhere('nominal', 'like', "%$search%")
            ->orWhere('keterangan', 'like', "%$search%");
      })
      ->orderBy('id', 'ASC')
      ->paginate($limit);

      return $bonus;
    }

    public function getTHP($nip):int {
        $karyawan = KaryawanModel::where('nip', $nip)
          ->first();
        $dateStart = Carbon::parse($karyawan->tgl_mulai);
        $dateNow = Carbon::now();
        $monthDiff = $dateNow->diffInMonths($dateStart);

        if($monthDiff < 12) {
          return $karyawan->gj_pokok * 2 / $monthDiff;
        } else{
          return $karyawan->gj_pokok * 2;
        }
    }

    public function getTHRId(){
        $tunjangan = TunjanganModel::where('nama_tunjangan', 'like', '%Tunjangan Hari Raya%')
          ->first();
        return $tunjangan->id;
    }

    public function store(array $data)
    {
        return ImportPenghasilanTidakTeraturModel::create([
            'nip' => $data['nip'],
            'id_tunjangan' => $this->getTHRId(),
            'bulan' => $data['bulan'],
            'tahun' => $data['tahun'],
            'nominal' => $this->getTHP($data['nip']),
            'keterangan' => $data['keterangan'] ?? '',
            'created_at' => now()
        ]);
    }
}
