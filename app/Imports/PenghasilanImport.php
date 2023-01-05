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
use Maatwebsite\Excel\Concerns\WithValidation;

class PenghasilanImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            $nip = $row['nip'];
            $bulan = $row['bulan'];
            $tahun = $row['tahun'];
            $t_uang_makan = ($row['t_uang_makan'] != null || $row['t_uang_makan'] != 0 || $row['t_uang_makan'] != "" ) ? $row['t_uang_makan'] : 0;
            $t_uang_pulsa = ($row['t_uang_pulsa'] != null || $row['t_uang_pulsa'] != 0 || $row['t_uang_pulsa'] != "" ) ? $row['t_uang_pulsa'] : 0;
            $t_uang_vitamin = ($row['t_uang_vitamin'] != null || $row['t_uang_vitamin'] != 0 || $row['t_uang_vitamin'] != "" ) ? $row['t_uang_vitamin'] : 0;
            $t_uang_transport = ($row['t_uang_transport'] != null || $row['t_uang_transport'] != 0 || $row['t_uang_transport'] != "" ) ? $row['t_uang_transport'] : 0;
            $t_uang_lembur = ($row['t_uang_lembur'] != null || $row['t_uang_lembur'] != 0 || $row['t_uang_lembur'] != "" ) ? $row['t_uang_lembur'] : 0;
            $pengganti_biaya_kesehatan = ($row['pengganti_biaya_kesehatan'] != null || $row['pengganti_biaya_kesehatan'] != 0 || $row['pengganti_biaya_kesehatan'] != "" ) ? $row['pengganti_biaya_kesehatan'] : 0;
            $uang_duka = ($row['uang_duka'] != null || $row['uang_duka'] != 0 || $row['uang_duka'] != "" ) ? $row['uang_duka'] : 0;
            $spd = ($row['spd'] != null || $row['spd'] != 0 || $row['spd'] != "" ) ? $row['spd'] : 0;
            $spd_pendidikan = ($row['spd_pendidikan'] != null || $row['spd_pendidikan'] != 0 || $row['spd_pendidikan'] != "" ) ? $row['spd_pendidikan'] : 0;
            $spd_pindah_tugas = ($row['spd_pindah_tugas'] != null || $row['spd_pindah_tugas'] != 0 || $row['spd_pindah_tugas'] != "" ) ? $row['spd_pindah_tugas'] : 0;
            $tunjangan_hari_raya = ($row['tunjangan_hari_raya'] != null || $row['tunjangan_hari_raya'] != 0 || $row['tunjangan_hari_raya'] != "" ) ? $row['tunjangan_hari_raya'] : 0;
            $jasa_produksi = ($row['jasa_produksi'] != null || $row['jasa_produksi'] != 0 || $row['jasa_produksi'] != "" ) ? $row['jasa_produksi'] : 0;
            $dana_pendidikan = ($row['dana_pendidikan'] != null || $row['dana_pendidikan'] != 0 || $row['dana_pendidikan'] != "" ) ? $row['dana_pendidikan'] : 0;

            try{
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 11,
                        'nominal' => $t_uang_transport,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 12,
                        'nominal' => $t_uang_pulsa,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 13,
                        'nominal' => $t_uang_vitamin,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 14,
                        'nominal' => $t_uang_makan,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 16,
                        'nominal' => $t_uang_lembur,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 17,
                        'nominal' => $pengganti_biaya_kesehatan,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 18,
                        'nominal' => $uang_duka,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 19,
                        'nominal' => $spd,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 20,
                        'nominal' => $spd_pendidikan,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 21,
                        'nominal' => $spd_pindah_tugas,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 22,
                        'nominal' => $tunjangan_hari_raya,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 23,
                        'nominal' => $jasa_produksi,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
                DB::table('penghasilan_tidak_teratur')
                    ->insert([
                        'nip' => $nip,
                        'id_tunjangan' => 24,
                        'nominal' => $dana_pendidikan,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'created_at' => now()
                    ]);
            } catch(Exception $e){

            }
        }        
    }
}
