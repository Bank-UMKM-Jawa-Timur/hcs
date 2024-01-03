<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenambahBrutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profiles = DB::table('mst_profil_kantor')
                        ->where('kd_cabang', '!=', '000')
                        ->pluck('id');
        for ($i=0; $i < count($profiles); $i++) { 
            DB::table('pemotong_pajak_tambahan')->insert([
                'id_profil_kantor' => $profiles[$i],
                'jkk' => 0.24,
                'jht' => 0.00,
                'jkm' => 0.30,
                'kesehatan' => 5.0,
                'kesehatan_batas_atas' => 12000000,
                'kesehatan_batas_bawah' => 4525479,
                'jp' => 0.0,
                'total' => 5.4,
                'active' => 1,
            ]);
        }
    }
}
