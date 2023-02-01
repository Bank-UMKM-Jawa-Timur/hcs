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

class MigrasiImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{
            foreach($collection as $e){
                DB::table('migrasi')
                    ->insert([
                        'nip' => $e['nip'],
                        'no_sk' => $e['no_sk'],
                        'jabatan_lama' => $e['jabatan_lama'],
                        'jabatan_baru' => $e['jabatan_baru'],
                        'keterangan' => $e['keterangan'],
                        'no_sp' => $e['no_sp'],
                        'sp_tanggal' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($e['sp_tanggal']),
                        'sp_pelanggaran' => $e['sp_pelanggaran'],
                        'sp_sanksi' => $e['sp_sanksi'],
                        'pjs_jabatan_asli' => $e['pjs_jabatan_asli'],
                        'pjs_jabatan' => $e['pjs_jabatan'],
                        'pjs_mulai' => $e['pjs_mulai'],
                        'pjs_berakhir' => $e['pjs_berakhir'],
                        'tipe' => $e['tipe'],
                    ]);
            }
        } catch(Exception $e){

        }
    }
}
