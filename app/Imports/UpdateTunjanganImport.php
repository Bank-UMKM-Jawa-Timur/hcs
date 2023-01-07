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

class UpdateTunjanganImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        DB::table('tunjangan_karyawan')->truncate();
        
        foreach($collection as $row){
            DB::beginTransaction();
            DB::table('mst_karyawan')
                ->where('nip', $row['nip'])
                ->update([
                    'gj_pokok' => $row['gj_pokok'],
                    'gj_penyesuaian' => $row['gj_penyesuaian'],
                    'updated_at' => now()
                ]);

            if($row['tj_keluarga'] != 0){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => 1,
                        'nominal' => $row['tj_keluarga']
                    ]);
            }
            if($row['tj_telairlis'] != 0){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => 2,
                        'nominal' => $row['tj_telairlis']
                    ]);
            }
            if($row['tj_jabatan'] != 0){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => 3,
                        'nominal' => $row['tj_jabatan']
                    ]);
            }
            if($row['tj_teller'] != 0){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => 4,
                        'nominal' => $row['tj_teller']
                    ]);
            }
            if($row['tj_perumahan'] != 0){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => 5,
                        'nominal' => $row['tj_perumahan']
                    ]);
            }
            if($row['tj_kemahalan'] != 0){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => 6,
                        'nominal' => $row['tj_kemahalan']
                    ]);
            }
            if($row['tj_pelaksana'] != 0){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => 7,
                        'nominal' => $row['tj_pelaksana']
                    ]);
            }
            if($row['tj_kesejahteraan'] != 0){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => 8,
                        'nominal' => $row['tj_kesejahteraan']
                    ]);
            }
            if($row['dpp'] != 0){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => 15,
                        'nominal' => $row['dpp']
                    ]);
            }
            DB::commit();
        }
    }
}
