<?php

namespace App\Exports;

use App\Repository\PenghasilanTidakTeraturRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportBiayaTidakTeratur implements FromView
{
    private PenghasilanTidakTeraturRepository $repo;

    public function __construct()
    {
        $this->repo = new PenghasilanTidakTeraturRepository;
    }
    public function view(): View
    {
        return view('exports.karyawan-tidak-teratur', [
            'data' => $this->repo->dataFileExcel()
        ]);
    }
}
