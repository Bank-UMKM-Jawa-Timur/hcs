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

class MigrasiPJSImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows 
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{
            foreach($collection as $item){
                DB::table('penjabat_sementara')
                    ->insert([
                        'nip' => $item['nip'],
                        'tanggal_mulai' => $item['tanggal_mulai'],
                        'tanggal_berakhir' => $item['tanggal_berakhir'],
                        'kd_jabatan' => $item['jabatan_asli'],
                        'kd_bagian' => $item['bagian'],
                        'no_sk' => $item['no_sk'],
                    ]);
            }
        } catch(Exception $e){

        }
    }
}
