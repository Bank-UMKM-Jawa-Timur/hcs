<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UpdateStatusImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row){
            $nik = $row['nik'];

            DB::table('mst_karyawan')
                ->where('nik', $nik)
                ->update([
                    'status' => $row['status'],
                    'no_rekening' => $row['no_rekening'],
                    'npwp' => $row['npwp'],
                    'kpj' => $row['kpj'],
                    'jkn' => $row['jkn']
                ]);
        }
    }
}
