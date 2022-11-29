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
                'kd_agama' => 'ISLM',
                'agama' => 'Islam',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'KRSTN',
                'agama' => 'Kristen',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'KAHLK',
                'agama' => 'Katholik',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'HNDU',
                'agama' => 'Hindu',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'BUDH',
                'agama' => 'Buddha',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'KHC',
                'agama' => 'Kong Hu Cu',
                'created_at' => now()
            ]);
    }
}
