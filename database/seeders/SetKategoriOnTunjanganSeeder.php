<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetKategoriOnTunjanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'mst_tunjangan';
        
        $tunjangan_arr = [
            "Keluarga",
            "Telpon, Air dan Listrik",
            "Jabatan",
            "Teller",
            "Perumahan",
            "Kemahalan",
            "Pelaksana",
            "Kesejahteraan",
            "Multi Level Entry",
            "TI",
            "Transport",
            "Pulsa",
            "Vitamin",
            "Uang Makan",
            "DPP",
            "Uang Lembur",
            "Pengganti Biaya Kesehatan",
            "Uang Duka",
            "SPD",
            "SPD Pendidikan",
            "SPD Pindah Tugas",
            "THR",
            "Jasa Produksi",
            "Dana Pendidikan",
            "Pengganti Uang Seragam",
            "Tambahan Penghasilan"
        ];

        $teratur = 'teratur';
        $tidak_teratur = 'tidak teratur';
        $bonus = 'bonus';

        $kategori_arr = [
            $teratur,
            $teratur,
            $teratur,
            $teratur,
            $teratur,
            $teratur,
            $teratur,
            $teratur,
            null,
            $teratur,
            $teratur,
            $teratur,
            $teratur,
            $teratur,
            null,
            $tidak_teratur,
            $tidak_teratur,
            $tidak_teratur,
            $tidak_teratur,
            $tidak_teratur,
            $tidak_teratur,
            $bonus,
            $bonus,
            $bonus,
            $tidak_teratur,
            $bonus,
        ];

        \DB::beginTransaction();
        try {
            for ($i=0; $i < count($tunjangan_arr); $i++) { 
                DB::table($table)
                    ->where('nama_tunjangan', $tunjangan_arr[$i])
                    ->whereNull('kategori')
                    ->update([
                        'kategori' => $kategori_arr[$i]
                    ]);
            }
            \DB::commit();
        } catch (\Exception $e) {
            throw $e;
            \DB::rollBack();
        }
    }
}
