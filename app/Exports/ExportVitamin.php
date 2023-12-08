<?php

namespace App\Exports;

use App\Models\CabangModel;
use App\Models\GajiPerBulanModel;
use App\Repository\PenghasilanTeraturRepository;
use Illuminate\Contracts\View\View;
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

        $data = $this->repo->excelVitamin($bulan, $tahun);
        return view('exports.vitamin', [
            'data' => $data,
            // 'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }
}
