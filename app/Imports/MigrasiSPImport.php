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

class MigrasiSPImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{
            foreach($collection as $item){
                $count = DB::table('mst_karyawan')
                    ->where('nip', $item['nip'])
                    ->count();
                if($count > 0){
                    DB::table('surat_peringatan')
                        ->insert([
                            'nip' => $item['nip'],
                            'no_sp' => $item['no_sp'],
                            'pelanggaran' => $item['pelanggaran'],
                            'sanksi' => $item['sanksi'],
                            'tanggal_sp' => ($item['tanggal_sp'] != null) ? Date::excelToDateTimeObject($item['tanggal_sp']) : null
                        ]);
                }
            }
        } catch(Exception $e){
            dd($e);
        }
    }
}
