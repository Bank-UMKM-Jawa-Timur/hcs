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
            CASE
            WHEN mst_karyawan.kd_jabatan='DIRUT' THEN 1
            WHEN mst_karyawan.kd_jabatan='DIRUMK' THEN 2
            WHEN mst_karyawan.kd_jabatan='DIRPEM' THEN 3
            WHEN mst_karyawan.kd_jabatan='DIRHAN' THEN 4
            WHEN mst_karyawan.kd_jabatan='KOMU' THEN 5
            WHEN mst_karyawan.kd_jabatan='KOM' THEN 7
            WHEN mst_karyawan.kd_jabatan='STAD' THEN 8
            WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 9
            WHEN mst_karyawan.kd_jabatan='PSD' THEN 10
            WHEN mst_karyawan.kd_jabatan='PC' THEN 11
            WHEN mst_karyawan.kd_jabatan='PBP' THEN 12
            WHEN mst_karyawan.kd_jabatan='PBO' THEN 13
            WHEN mst_karyawan.kd_jabatan='PEN' THEN 14
            WHEN mst_karyawan.kd_jabatan='ST' THEN 15
            WHEN mst_karyawan.kd_jabatan='NST' THEN 16
            WHEN mst_karyawan.kd_jabatan='IKJP' THEN 17 END ASC
        ";
    }

    public function getAllKaryawan($search, $limit=10, $page=1)
    {
        return $this->getDataKaryawan($search, $limit, $page);
    }

    public function getDataKaryawan($search, $limit=10, $page=1) {
        $search = strtolower($search);

        if (auth()->user()->hasRole('cabang')) {
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
                'mst_karyawan.kd_entitas',
            )
            ->leftJoin('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
            ->with('jabatan')
            ->with('bagian')
            ->where('kd_entitas', auth()->user()->kd_cabang)
            ->whereNull('tanggal_penonaktifan')
            ->where(function ($query) use ($search) {
                $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nik', 'like', "%$search%")
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
            ->paginate($limit);
        }else{
            $is_pusat = auth()->user()->hasRole('kepegawaian');
            $kd_cabang = DB::table('mst_cabang')
                            ->select('kd_cabang')
                            ->pluck('kd_cabang')
                            ->toArray();

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
                'mst_karyawan.kd_entitas',
            )
            ->leftJoin('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
            ->with('jabatan')
            ->with('bagian')
            ->whereNull('tanggal_penonaktifan')
            ->where(function ($query) use ($search, $kd_cabang) {
                $query->where('mst_karyawan.nama_karyawan', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nip', 'like', "%$search%")
                    ->orWhere('mst_karyawan.nik', 'like', "%$search%")
                    ->orWhere('mst_karyawan.kd_bagian', 'like', "%$search%")
                    ->orWhere('mst_karyawan.kd_jabatan', 'like', "%$search%")
                    ->orWhere('mst_karyawan.kd_entitas', 'like', "%$search%")
                    ->orWhere('mst_karyawan.status_jabatan', 'like', "%$search%")
                    ->orWhere('c.kd_cabang', 'like', "%$search%")
                    ->orWhere(function($q2) use ($search, $kd_cabang) {
                        if (str_contains($search, 'pusat')) {
                            $q2->whereNotIn('mst_karyawan.kd_entitas', $kd_cabang)
                            ->orWhereNull('mst_karyawan.kd_entitas');
                        }
                        else {
                            $q2->where('c.nama_cabang', 'like', "%$search%");
                        }
                    })
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
            })
            ->orderByRaw($this->orderRaw)
            ->orderBy('kd_cabang', 'asc')
            ->orderBy('nip', 'asc')
            ->paginate($limit);
        }

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

    public function getDataKaryawanExport() {
        $karyawan = KaryawanModel::select(
            'mst_karyawan.nip',
            'mst_karyawan.nama_karyawan',
            'mst_karyawan.no_rekening',
            'mst_karyawan.npwp',
            'mst_karyawan.status_ptkp',
            'mst_karyawan.kd_bagian',
            'mst_karyawan.kd_jabatan',
            'mst_karyawan.kd_entitas',
            'mst_karyawan.tanggal_penonaktifan',
            'mst_karyawan.status_jabatan',
            'mst_karyawan.ket_jabatan',
            'mst_karyawan.kd_entitas',
            DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang) LIMIT 1), 1, 0) AS status_kantor")
        )
        ->leftJoin('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
        ->with('jabatan')
        ->with('bagian')
        ->whereNull('tanggal_penonaktifan')
        ->where(function($query) {
            $query->whereNull('mst_karyawan.status_ptkp')
                ->orWhereNull('mst_karyawan.no_rekening')
                ->orWhereNull('mst_karyawan.npwp');
        })
        ->orderBy('status_kantor', 'asc')
        ->orderByRaw($this->orderRaw)
        ->orderBy('mst_karyawan.kd_entitas')
        ->get();

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
            ->orderBy('kd_entitas', 'asc')
            ->get();

        $this->addEntity($karyawan);
        return $karyawan;
    }

    public function getAllKaryawanNonaktif($search, $limit=10)
    {
        return $this->getKaryawanPusatNonaktif($search, $limit);
    }

    public function filterKaryawanPusatNonaktif($start_date, $end_date, $limit, $page, $search)
    {
        $karyawan = KaryawanModel::with('jabatan')
            ->with('bagian')
            ->whereNotNull('tanggal_penonaktifan')
            ->orderBy('tanggal_penonaktifan', 'DESC')
            ->whereBetween('tanggal_penonaktifan', [$start_date, $end_date])
            // ->when($search, function($query) use ($search) {
            //     $query->where('karyawan.nip', 'LIKE', "%$search%")
            //         ->orWhere('karyawan.nama_karyawan', 'LIKE', "%$search%");
            // })
            ->paginate($limit);

        $karyawan->appends([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'page_length' => $limit,
        ]);


        $this->addEntity($karyawan);
        return $karyawan;
    }

    public function getKaryawanPusatNonaktif($search, $limit=10)
    {
        $karyawan = KaryawanModel::with('jabatan')
            ->with('bagian')
            ->select(
                'mst_karyawan.*',
                DB::raw("
                    IF((cabang.nama_cabang != 'NULL' AND IFNULL(div.nama_divisi, '-') = '-' AND IFNULL(sub_div.nama_subdivisi, '-') = '-'),
                        CONCAT('Cab.', cabang.nama_cabang),
                        IF((IFNULL(div.nama_divisi, '-') != '-' AND IFNULL(sub_div.nama_subdivisi, '-') = '-'), CONCAT(div.nama_divisi, ' (Pusat)'),
                            IF(IFNULL(sub_div.nama_subdivisi, '-') != '-', CONCAT(sub_div.nama_subdivisi, ' (Pusat)'), '-')
                        )
                    ) AS kantor_terakhir
                ")
            )
            ->leftJoin('mst_divisi as div', 'div.kd_divisi', 'mst_karyawan.kd_entitas')
            ->leftJoin('mst_sub_divisi as sub_div', 'sub_div.kd_subdiv', 'mst_karyawan.kd_entitas')
            ->leftJoin('mst_cabang as cabang', 'cabang.kd_cabang', 'mst_karyawan.kd_entitas')
            ->whereNotNull('tanggal_penonaktifan')
            ->when($search, function($query) use ($search) {
                $query->where(function($q2) use ($search) {
                    $q2->having('kantor_terakhir', 'LIKE', "%$search%")
                        ->orWhere('mst_karyawan.nip', $search)
                        ->orWhere('mst_karyawan.nama_karyawan', 'LIKE', "%$search%")
                        ->orWhere('mst_karyawan.nik', $search)
                        ->orWhere('mst_karyawan.ket_jabatan', 'like', "%$search%")
                        ->orWhere('mst_karyawan.kategori_penonaktifan', 'like', "%$search%");
                })
                ->orWhere(function($q2) use ($search) {
                    $q2->orWhereHas('jabatan', function($query3) use ($search) {
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
                })
                ->orWhereDate('mst_karyawan.tanggal_penonaktifan', date('Y-m-d', strtotime($search)));
            })
            ->orderBy('tanggal_penonaktifan', 'DESC')
            ->paginate($limit);

        $this->addEntity($karyawan);
        foreach ($karyawan as $key => $krywn) {
            $krywn->prefix = match($krywn->status_jabatan) {
                'Penjabat' => 'Pj. ',
                'Penjabat Sementara' => 'Pjs. ',
                default => '',
            };

            $jabatan = $krywn->jabatan->nama_jabatan;

            $krywn->ket = $krywn->ket_jabatan ? "({$krywn->ket_jabatan})" : "";

            if(isset($krywn->entitas->subDiv)) {
                $krywn->entitas_result = $krywn->entitas->subDiv->nama_subdivisi;
            } else if(isset($krywn->entitas->div)) {
                $krywn->entitas_result = $krywn->entitas->div->nama_divisi;
            } else {
                $krywn->entitas_result = '';
            }

            if ($jabatan == "Pemimpin Sub Divisi") {
                $jabatan = 'PSD';
            } else if ($jabatan == "Pemimpin Bidang Operasional") {
                $jabatan = 'PBO';
            } else if ($jabatan == "Pemimpin Bidang Pemasaran") {
                $jabatan = 'PBP';
            } else {
                $jabatan = $krywn->jabatan->nama_jabatan;
            }

            $krywn->jabatan_result = $jabatan;
        }

        return $karyawan;
    }

    private function addEntity($karyawan): void
    {
        $karyawan->map(fn($karyawan) => $karyawan->entitas = EntityService::getEntity($karyawan->kd_entitas));
    }

    public function getEntity($karyawan) :void
    {
        $this->addEntity($karyawan);
    }
}
