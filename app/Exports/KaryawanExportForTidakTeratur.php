<?php

namespace App\Exports;

use App\Repository\PenghasilanTidakTeraturRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;

class KaryawanExportForTidakTeratur implements FromCollection
{
    private PenghasilanTidakTeraturRepository $repo;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct()
    {
        $this->repo = new PenghasilanTidakTeraturRepository;
    }

    public function view(): View
    {
        return view('penghasilan.import_tidak_teratur.excel-export-karyawan', [
            'data' => $this->repo->dataFileExcel()
        ]);
    }
}
