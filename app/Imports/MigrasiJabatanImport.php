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
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MigrasiJabatanImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
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
                DB::table('migrasi_jabatan')
                    ->insert([
                        'nip' => $item['nip'],
                        'tgl' => ($item['tgl'] != null) ? Date::excelToDateTimeObject($item['tgl']) : null,
                        'no_sk' => $item['no_sk'],
                        'keterangan' => $item['keterangan'],
                        'lama' => $item['lama'],
                        'baru' => $item['baru'],
                        'created_at' => now()
                    ]);
            }
        } catch(Exception $e){
            dd($e);
        } catch(Exception $e){
            dd($e);
        }
    }
}
