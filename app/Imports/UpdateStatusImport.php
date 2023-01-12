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
            $status = $row['status'];

            if($status == 'TK'){
                $status = 'TK';
                DB::table('mst_karyawan')
                    ->where('nik', $nik)
                    ->update([
                        'status' => $status,
                        'no_rekening' => $row['no_rekening'],
                        'npwp' => $row['npwp'],
                        'kpj' => $row['kpj'],
                        'jkn' => $row['jkn']
                    ]);
            } else{
                $arr = str_split($status);
                $status = $arr[0];
                $jml = $arr[2];
                $is = DB::table('mst_karyawan')
                    ->where('nik', $nik)
                    ->first('id_is');
                if($is == null){
                    DB::table('is')
                        ->insert([
                            'is_jml_anak' => $jml
                        ]);
                    $id = DB::table('is')
                        ->orderBy('id', 'desc')
                        ->first('id');
                    DB::table('mst_karyawan')
                    ->where('nik', $nik)
                    ->update([
                        'status' => $status,
                        'no_rekening' => $row['no_rekening'],
                        'npwp' => $row['npwp'],
                        'kpj' => $row['kpj'],
                        'jkn' => $row['jkn'],
                        'id_is' => $id->id
                    ]);
                } else{
                    DB::table('is')
                        ->where('id', $is)
                        ->update([
                            'is_jml_anak' => $jml
                        ]);
                    DB::table('mst_karyawan')
                    ->where('nik', $nik)
                    ->update([
                        'status' => $status,
                        'no_rekening' => $row['no_rekening'],
                        'npwp' => $row['npwp'],
                        'kpj' => $row['kpj'],
                        'jkn' => $row['jkn']
                    ]);
                }
            }
        }
    }
}
