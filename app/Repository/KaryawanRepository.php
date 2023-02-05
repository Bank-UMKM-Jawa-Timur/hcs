<?php

namespace App\Repository;

use App\Models\CabangModel;
use App\Models\KaryawanModel;
use App\Service\EntityService;
use Illuminate\Support\Collection;

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

    public function getAllKaryawan(): Collection
    {
        return $this->getKaryawanPusat()
            ->push(...$this->getKaryawanCabang());
    }

    public function getKaryawanPusat(): Collection
    {
        $karyawan = KaryawanModel::with('jabatan')
            ->with('bagian')
            ->whereNotIn('kd_entitas', $this->cabang)
            ->orWhere('kd_entitas', null)
            ->orderByRaw($this->orderRaw)
            ->get();

        $this->addEntity($karyawan);
        return $karyawan;
    }

    public function getKaryawanCabang(): Collection
    {
        $karyawan = KaryawanModel::with('jabatan')
            ->with('bagian')
            ->whereIn('kd_entitas', $this->cabang)
            ->orderByRaw($this->orderRaw)
            ->get();

        $this->addEntity($karyawan);
        return $karyawan;
    }

    private function addEntity(Collection $karyawan): void
    {
        $karyawan->map(fn($karyawan) => $karyawan->entitas = EntityService::getEntity($karyawan->kd_entitas));
    }
}
