<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengurangBrutoSeeder extends Seeder
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
            DB::table('pemotong_pajak_pengurangan')->insert([
                'id_profil_kantor' => $profiles[$i],
                'dpp' => 5.00,
                'jp' => 1.00,
                'jp_jan_feb' => 9077600,
                'jp_mar_des' => 9559600,
                'active' => 1,
            ]);
        }
    }
}
