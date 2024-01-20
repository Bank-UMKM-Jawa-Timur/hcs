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
                        ->orderBy('kd_cabang')
                        ->pluck('id')
                        ->toArray();
        $kesehatan_batas_bawah = [
            4725479,
            4725479,
            2221135,
            2240701,
            2638628,
            2281469,
            2806955,
            4635133,
            3368275,
            2241054,
            2256050,
            2340668,
            4624787,
            2945544,
            2258455,
            2243291,
            2320000,
            2223163,
            2235311,
            4642031,
            2828323,
            2238808,
            2199337,
            4638582,
            2665392,
            2864225,
            3155367,
            2172287,
            2371016,
            2183590,
            2249113,
            3368275,
            2182861
        ];
        for ($i=0; $i < count($profiles); $i++) { 
            DB::table('pemotong_pajak_tambahan')->insert([
                'id_profil_kantor' => $profiles[$i],
                'jkk' => 0.24,
                'jht' => 0.00,
                'jkm' => 0.30,
                'kesehatan' => 5.0,
                'kesehatan_batas_atas' => 12000000,
                'kesehatan_batas_bawah' => $kesehatan_batas_bawah[$i],
                'jp' => 0.0,
                'total' => 5.4,
                'active' => 1,
            ]);
        }
    }
}
