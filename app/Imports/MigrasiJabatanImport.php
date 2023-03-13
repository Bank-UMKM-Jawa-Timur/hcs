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
            if(count($collection) == 5){
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
            } else{
                foreach($collection as $i => $item){
                    DB::table('demosi_promosi_pangkat')
                    ->insert([
                        'nip' => $item['nip'],
                        'tanggal_pengesahan' => ($item['tanggal_pengesahan'] != null) ? Date::excelToDateTimeObject($item['tanggal_pengesahan']) : null,
                        'bukti_sk' => $item['bukti_sk'],
                        'keterangan' => $item['keterangan'],
                        'kd_entitas_lama' => ($item['entitas_lama'] != null) ? $item['entitas_lama'] : null,
                        'kd_entitas_baru' => ($item['entitas_baru'] != null) ? $item['entitas_baru'] : null,
                        'kd_jabatan_lama' => ($item['jabatan_lama'] != null) ? $item['jabatan_lama'] : null,
                        'kd_jabatan_baru' => ($item['jabatan_baru'] != null) ? $item['jabatan_baru'] : null,
                        'kd_bagian' => ($item['bagian_baru'] != null) ? $item['bagian_baru'] : null,
                        'kd_bagian_lama' => ($item['bagian_lama'] != null) ? $item['bagian_lama'] : null,
                        'kd_panggol_lama' => $item['panggol_lama'],
                        'kd_panggol_baru' => $item['panggol_baru'],
                        'status_jabatan_lama' => $item['status_jabatan_lama'],
                        'status_jabatan_baru' => $item['status_jabatan_baru'],
                        'nip_lama' => ($item['nip_lama'] != null) ? $item['nip_lama'] : null,
                        'nip_baru' => ($item['nip_baru'] != null) ? $item['nip_baru'] : null,
                        'created_at' => now()
                    ]);
                }
            }
        } catch(Exception $e){
            dd($e);
        } catch(Exception $e){
            dd($e);
        }
    }
}
