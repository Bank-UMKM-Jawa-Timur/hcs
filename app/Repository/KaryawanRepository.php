<?php

namespace App\Repository;

use App\Http\Controllers\Utils\PaginationController;
use App\Models\CabangModel;
use App\Models\KaryawanModel;
use App\Service\EntityService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KaryawanRepository
{
    private \Illuminate\Support\Collection $cabang;
    private String $orderRaw;

    public function __construct()
    {
        $this->cabang = CabangModel::pluck('kd_cabang');
        $this->orderRaw = "
            CASE WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 1
            WHEN mst_karyawan.kd_jabatan='PSD' THEN 2
            WHEN mst_karyawan.kd_jabatan='PC' THEN 3
            WHEN mst_karyawan.kd_jabatan='PBP' THEN 4
            WHEN mst_karyawan.kd_jabatan='PBO' THEN 5
            WHEN mst_karyawan.kd_jabatan='PEN' THEN 6
            WHEN mst_karyawan.kd_jabatan='ST' THEN 7
            WHEN mst_karyawan.kd_jabatan='NST' THEN 8
            WHEN mst_karyawan.kd_jabatan='IKJP' THEN 9 END ASC
        ";
    }

    public function getAllKaryawan($search, $limit=10, $page=1)
    {
        return $this->getDataKaryawan($search, $limit, $page);
        // return $this->getKaryawanPusat($limit=10)
        //     ->push(...$this->getKaryawanCabang($limit=10))->toArray();
    }

    public function getDataKaryawan($search, $limit=10, $page=1) {
        $karyawan = KaryawanModel::select(
            'mst_karyawan.nip',
            'mst_karyawan.nik',
            'mst_karyawan.nama_karyawan',
            'mst_karyawan.kd_bagian',
            'mst_karyawan.kd_jabatan',
            'mst_karyawan.kd_entitas',
            'mst_karyawan.tanggal_penonaktifan',
            'mst_karyawan.status_jabatan',
            'mst_karyawan.ket_jabatan',
            DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor")
        )
        ->leftJoin('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
        ->with('jabatan')
        ->with('bagian')
        ->whereNull('tanggal_penonaktifan')
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
        ->orderByRaw($this->orderRaw)
        ->orderByRaw('IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0)')
        ->paginate($limit);

        $this->addEntity($karyawan);

        foreach ($karyawan as $key => $value) {
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
        return $karyawan;
    }

    public function getKaryawanPusat($limit): Collection
    {
        $karyawan = KaryawanModel::select(
                'mst_karyawan.nip',
                'mst_karyawan.nik',
                'mst_karyawan.nama_karyawan',
                'mst_karyawan.kd_bagian',
                'mst_karyawan.kd_jabatan',
                'mst_karyawan.kd_entitas',
                'mst_karyawan.tanggal_penonaktifan',
            )
            ->with('jabatan')
            ->with('bagian')
            ->whereNull('tanggal_penonaktifan')
            ->whereNotIn('kd_entitas', $this->cabang)
            ->orWhere('kd_entitas', null)
            ->orderByRaw($this->orderRaw)
            ->get();

        $this->addEntity($karyawan);
        return $karyawan;
    }

    public function getKaryawanCabang($limit): Collection
    {
        $karyawan = KaryawanModel::select(
                'mst_karyawan.nip',
                'mst_karyawan.nik',
                'mst_karyawan.nama_karyawan',
                'mst_karyawan.kd_bagian',
                'mst_karyawan.kd_jabatan',
                'mst_karyawan.kd_entitas',
                'mst_karyawan.tanggal_penonaktifan',
            )
            ->with('jabatan')
            ->with('bagian')
            ->whereNull('tanggal_penonaktifan')
            ->whereIn('kd_entitas', $this->cabang)
            ->orderByRaw($this->orderRaw)
            ->get();

        $this->addEntity($karyawan);
        return $karyawan;
    }

    public function getAllKaryawanNonaktif(): Collection
    {
        return $this->getKaryawanPusatNonaktif();
    }

    public function filterKaryawanPusatNonaktif($start_date, $end_date): Collection
    {
        $karyawan = KaryawanModel::with('jabatan')
            ->with('bagian')
            ->whereNotNull('tanggal_penonaktifan')
            ->orderBy('tanggal_penonaktifan', 'DESC')
            ->whereBetween('tanggal_penonaktifan', [$start_date, $end_date])
            ->get();

        $this->addEntity($karyawan);
        return $karyawan;
    }

    public function getKaryawanPusatNonaktif(): Collection
    {
        $karyawan = KaryawanModel::with('jabatan')
            ->with('bagian')
            ->whereNotNull('tanggal_penonaktifan')
            ->orderBy('tanggal_penonaktifan', 'DESC')
            ->get();

        $this->addEntity($karyawan);
        return $karyawan;
    }

    private function addEntity($karyawan): void
    {
        $karyawan->map(fn($karyawan) => $karyawan->entitas = EntityService::getEntity($karyawan->kd_entitas));
    }
}
