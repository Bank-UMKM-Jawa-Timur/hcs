<?php

namespace App\Imports;

use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MigrasiPJSImport implements ToCollection, WithHeadingRow, SkipsEmptyRows 
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{
            foreach($collection as $i => $item){
                // dd($item);
                DB::table('pejabat_sementara')
                    ->insert([
                        'nip' => $item['nip'],
                        'tanggal_mulai' => ($item['tanggal_mulai'] != null) ? Date::excelToDateTimeObject($item['tanggal_mulai']) : null,
                        'tanggal_berakhir' => ($item['tanggal_berakhir'] != null) ? Date::excelToDateTimeObject($item['tanggal_berakhir']) : null,
                        'kd_jabatan' => $item['kd_jabatan'],
                        'kd_entitas' => ($item['kd_entitas'] != null) ? $item['kd_entitas'] : null,
                        'kd_bagian' => ($item['kd_bagian'] != null) ? $item['kd_bagian'] : null,
                        'no_sk' => $item['no_sk'],
                        'created_at' => now()
                    ]);
            }
        } catch(Exception $e){
            dd($e);
        } catch(QueryException $e){
            dd($e);
        }
    }
}
