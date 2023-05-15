<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengkinianDataImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $arrayTunjangan = array();
        $arrayDataKeluarga = array();
        $arrayKaryawan = array();
        foreach($collection as $item){
            if($item['']);
        }
    }
}
