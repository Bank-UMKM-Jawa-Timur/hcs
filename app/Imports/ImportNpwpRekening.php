<?php

namespace App\Imports;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportNpwpRekening implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{
            foreach($collection as $row){
                $nip = $row['nip'];
                
                if($row['no_rekening'] != null || $row['no_rekening'] != ''){
                    DB::table('mst_karyawan')
                        ->where('nip', $nip)
                        ->update([
                            'no_rekening' => $row['no_rekening']
                        ]);
                }
                if($row['npwp'] != null || $row['npwp'] != ''){
                    DB::table('mst_karyawan')
                        ->where('nip', $nip)
                        ->update([
                            'npwp' => $row['npwp']
                        ]);
                }

                if($row['status'] != null || $row['status'] != ''){
                    $status = 'Belum Kawin';
                    $jumlahAnak = 0;
                    if($row['status'] != 'TK/0'){
                        $status = 'Kawin';
                        $jmlAnak = explode('/', $row['status']);
                        $jumlahAnak = $jmlAnak[1];
                    }

                    DB::table('mst_karyawan')
                        ->where('nip', $nip)
                        ->update([
                            'status' => $status
                        ]);

                    // Cek data di table keluarga
                    $countData = DB::table('keluarga')
                        ->where('nip', $nip)
                        ->count();
                    if($countData < 1) {
                        DB::table('keluarga')
                            ->insert([
                                'jml_anak' => $jumlahAnak,
                                'nip' => $nip
                            ]);
                    } else {
                        DB::table('keluarga')
                            ->where('nip', $nip)
                            ->update([
                                'jml_anak' => $jumlahAnak
                            ]);
                    }
                }
            }
        } catch(Exception $e){
            DB::rollBack();
            dd($e);
        } catch(QueryException $e){
            DB::rollBack();
            dd($e);
        }
    }
}
