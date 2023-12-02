<?php

namespace App\Repository;

use App\Models\ImportPenghasilanTidakTeraturModel;
use App\Models\KaryawanModel;
use App\Models\SpModel;
use Carbon\Carbon;
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
            'id_tunjangan' => $data['id_tunjangan'],
            'bulan' => $data['bulan'],
            'tahun' => $data['tahun'],
            'nominal' => str_replace('.', '', $data['nominal']),
            'keterangan' => $data['keterangan'] ?? null,
            'created_at' => now()
        ]);
    }

    public function storeUangDuka(array $data)
    {
        return ImportPenghasilanTidakTeraturModel::create([
            'nip' => $data['nip'],
            'id_tunjangan' => $data['id_tunjangan'],
            'bulan' => $data['bulan'],
            'tahun' => $data['tahun'],
            'nominal' => str_replace('.', '', $data['nominal']),
            'keterangan' => $data['keterangan'] . " meninggal",
            'created_at' => now()
        ]);
    }

    public function getAllPenghasilan($search, $limit=10, $page=1){
        $karyawanRepo = new KaryawanRepository();
        $penghasilan = KaryawanModel::select('nama_tunjangan', 'id_tunjangan', 'nominal', 'tahun', 'bulan', 'keterangan', 'penghasilan_tidak_teratur.created_at','mst_karyawan.nip', 'mst_karyawan.nik', 'mst_karyawan.nama_karyawan', 'mst_karyawan.kd_bagian', 'mst_karyawan.kd_jabatan', 'mst_karyawan.kd_entitas', 'mst_karyawan.tanggal_penonaktifan', 'mst_karyawan.status_jabatan', 'mst_karyawan.ket_jabatan', DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor"))
            ->join('penghasilan_tidak_teratur', 'mst_karyawan.nip', 'penghasilan_tidak_teratur.nip')
            ->join('mst_tunjangan', 'penghasilan_tidak_teratur.id_tunjangan', 'mst_tunjangan.id')
            ->leftJoin('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
            ->with('jabatan')
            ->with('bagian')
            ->whereNull('tanggal_penonaktifan')
            ->where('nominal', '>', 0)
            ->where(function ($query) use ($search) {
                $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nik', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                    ->orWhere('mst_karyawan.kd_bagian', 'like', "%$search%")
                    ->orWhere('mst_karyawan.kd_jabatan', 'like', "%$search%")
                    ->orWhere('mst_karyawan.kd_entitas', 'like', "%$search%")
                    ->orWhere('mst_karyawan.status_jabatan', 'like', "%$search%")
                    ->orWhere('c.kd_cabang', 'like', "%$search%")
                    ->orWhere('c.nama_cabang', 'like', "%$search%")
                    ->orWhere('mst_karyawan.ket_jabatan', 'like', "%$search%")
                    ->orWhereHas('jabatan', function($query3) use ($search) {
                        $query3->where("nama_jabatan", 'like', "%$search%");
                    })
                    ->orWhereHas('bagian', function($query3) use ($search) {
                        $query3->where("nama_bagian", 'like', "%$search%");
                    })
                    ->orWhere(function($query2) use ($search) {
                        $query2->orWhereHas('jabatan', function($query3) use ($search) {
                            $query3->where("nama_jabatan", 'like', "%$search%")
                                ->orWhereRaw("MATCH(nama_jabatan) AGAINST('$search')");
                        })
                        ->whereHas('bagian', function($query3) use ($search) {
                            $query3->whereRaw("MATCH(nama_bagian) AGAINST('$search')")
                                ->orWhereRaw("MATCH(nama_bagian) AGAINST('$search')");
                        });
                    });
    
                if ($search == 'pusat') {
                    $query->orWhereRaw('mst_karyawan.kd_entitas NOT IN(SELECT kd_cabang FROM mst_cabang)');
                }
            })
            ->orderBy('penghasilan_tidak_teratur.created_at', 'desc')
            ->paginate($limit);

            $karyawanRepo->getEntity($penghasilan);

            foreach ($penghasilan as $key => $value) {
                $prefix = match ($value->status_jabatan) {
                    'Penjabat' => 'Pj. ',
                    'Penjabat Sementara' => 'Pjs. ',
                    default => '',
                };
                
                if ($value->jabatan) {
                    $jabatan = $value->jabatan->nama_jabatan;
                } else {
                    $jabatan = 'undifined';
                }
                
                $ket = $value->ket_jabatan ? "({$value->ket_jabatan})" : '';
                
                if (isset($value->entitas->subDiv)) {
                    $entitas = $value->entitas->subDiv->nama_subdivisi;
                } elseif (isset($value->entitas->div)) {
                    $entitas = $value->entitas->div->nama_divisi;
                } else {
                    $entitas = '';
                }
                
                if ($jabatan == 'Pemimpin Sub Divisi') {
                    $jabatan = 'PSD';
                } elseif ($jabatan == 'Pemimpin Bidang Operasional') {
                    $jabatan = 'PBO';
                } elseif ($jabatan == 'Pemimpin Bidang Pemasaran') {
                    $jabatan = 'PBP';
                } else {
                    $jabatan = $value->jabatan ? $value->jabatan->nama_jabatan : 'undifined';
                }
    
                $display_jabatan = $prefix . ' ' . $jabatan . ' ' . $entitas . ' ' . $value?->bagian?->nama_bagian . ' ' . $ket;
                $value->display_jabatan = $display_jabatan;
            }
            return $penghasilan;
    }
}
