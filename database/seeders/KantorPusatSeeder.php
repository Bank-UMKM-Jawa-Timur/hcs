<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KantorPusatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_cabang')->insert([
            'kd_cabang' => '000',
            'nama_cabang' => 'Pusat',
            'alamat_cabang' => 'JL. Ciliwung No.11',
            'id_kantor' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
