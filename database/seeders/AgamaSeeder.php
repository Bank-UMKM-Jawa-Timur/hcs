<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'I',
                'agama' => 'Islam',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'K',
                'agama' => 'Kristen',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'KT',
                'agama' => 'Katholik',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'H',
                'agama' => 'Hindu',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'B',
                'agama' => 'Buddha',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'KH',
                'agama' => 'Kong Hu Cu',
                'created_at' => now()
            ]);
    }
}
