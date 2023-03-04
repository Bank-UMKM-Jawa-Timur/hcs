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

class MigrasiJabatanImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{
            foreach($collection as $i => $item){
                DB::table('migrasi')
                    ->insert([
                        'nip' => $item['nip'],
                        'jabatan_tanggal' => $item['tanggal_pengesahan'],
                        'no_sk' => $item['bukti_sk'],
                        'jabatan_lama' => $item['jabatan_lama'],
                        'jabatan_baru' => $item['jabatan_baru'],
                        'kd_entitas_lama' => $item['entitas_lama'],
                        'kd_entitas_baru' => $item['entitas_baru'],
                        'keterangan' => $item['keterangan'],
                        'kd_bagian_lama' => $item['bagian_lama'],
                        'kd_bagian_baru' => $item['bagian_baru'],
                        'panggol_lama' => $item['panggol_lama'],
                        'panggol_baru' => $item['panggol_baru'],
                        'status_jabatan_lama' => $item['status_jabatan_lama'],
                        'status_jabatan_baru' => $item['status_jabatan_baru'],
                        'nip_lama' => $item['nip_lama'],
                        'nip_baru' => $item['nip_baru'],
                        'tipe' => 'Jabatan'
                    ]);
            }
        } catch(Exception $e){

        }
    }
}
