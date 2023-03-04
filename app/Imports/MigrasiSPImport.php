<?php

namespace App\Imports;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MigrasiSPImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{
            foreach($collection as $item){
                DB::table('migrasi')
                    ->insert([
                        'nip' => $item['nip'],
                        'no_sp' => $item['no_sp'],
                        'sp_pelanggaran' => $item['pelanggaran'],
                        'sp_sanksi' => $item['sanksi'],
                        'sp_tanggal' => $item['tanggal_sp']
                    ]);
            }
        } catch(Exception $e){

        }
    }
}
