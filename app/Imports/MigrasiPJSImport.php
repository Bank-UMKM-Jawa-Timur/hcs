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
                DB::table('migrasi')
                    ->insert([
                        'nip' => $item['nip'],
                        'pjs_mulai' => $item['tanggal_mulai'],
                        'pjs_berakhir' => $item['tanggal_berakhir'],
                        'pjs_jabatan_asli' => $item['jabatan_asli'],
                        'pjs_jabatan' => $item['jabatan'],
                        'pjs_entitas' => $item['entitas'],
                        'pjs_bagian' => $item['bagian'],
                        'no_sk' => $item['no_sk'],
                        'tipe' => 'pjs'
                    ]);
            }
        } catch(Exception $e){

        }
    }
}
