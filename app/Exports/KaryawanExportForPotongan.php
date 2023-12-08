<?php

namespace App\Exports;

use App\Repository\PenghasilanTidakTeraturRepository;
use App\Repository\PotonganRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KaryawanExportForPotongan implements FromView
{
    private PotonganRepository $repo;

    public function __construct()
    {
        $this->repo = new PotonganRepository;
    }
    public function view(): View
    {
        return view('potongan.excel-export-karyawan', [
            'data' => $this->repo->dataFileExcel()
        ]);
    }
}
