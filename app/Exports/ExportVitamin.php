<?php

namespace App\Exports;

use App\Models\CabangModel;
use App\Models\GajiPerBulanModel;
use App\Models\KaryawanModel;
use App\Repository\PenghasilanTeraturRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ExportVitamin implements FromView
{
    private PenghasilanTeraturRepository $repo;

    public function __construct()
    {
        $this->repo = new PenghasilanTeraturRepository;
    }
    public function view(): View
    {
        $bulan = Request()->bulan;
        $tahun = Request()->tahun;
        $cabang = CabangModel::select('kd_cabang')->get();

        $ttdKaryawan = KaryawanModel::select(
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
            DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang) LIMIT 1), 1, 0) AS status_kantor")
        )
            ->with('jabatan')
            ->with('bagian')
            ->whereIn('kd_jabatan', ['PIMDIV', 'PSD'])
            ->whereIn('kd_entitas', ['UMUM', 'SDM'])
            ->whereNull('tanggal_penonaktifan')
            ->get();
        foreach ($ttdKaryawan as $key => $krywn) {
            $krywn->prefix = match ($krywn->status_jabatan) {
                'Penjabat' => 'Pj. ',
                'Penjabat Sementara' => 'Pjs. ',
                default => '',
            };

            $jabatan = $krywn->jabatan->nama_jabatan;

            $krywn->ket = $krywn->ket_jabatan ? "({$krywn->ket_jabatan})" : "";

            if (isset($krywn->entitas->subDiv)) {
                $krywn->entitas_result = $krywn->entitas->subDiv->nama_subdivisi;
            } else if (isset($krywn->entitas->div)) {
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

        $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
        if (auth()->user()->hasRole('cabang')) {
            $pincab = DB::table('mst_karyawan')->where('kd_jabatan', 'PC')->where('kd_entitas', $kd_entitas)->first();
            $cabang = DB::table('mst_cabang')->select('kd_cabang', 'nama_cabang')->where('kd_cabang', $kd_entitas)->first();
        } else {
            $pincab = null;
            $cabang = null;
        }

        $data = $this->repo->excelVitamin($bulan, $tahun);
        return view('exports.vitamin', [
            'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'pincab' => $pincab,
            'cabang' => $cabang,
            'ttdKaryawan' => $ttdKaryawan
        ]);
    }
}
