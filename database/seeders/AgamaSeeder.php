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
                'kd_agama' => 'Islam',
                'agama' => 'Islam',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'Kristed',
                'agama' => 'Kristed',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'Katholik',
                'agama' => 'Katholik',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'Hindu',
                'agama' => 'Hindu',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'Buddha',
                'agama' => 'Buddha',
                'created_at' => now()
            ]);
        DB::table('mst_agama')
            ->insert([
                'kd_agama' => 'Kong Hu Cu',
                'agama' => 'Kong Hu Cu',
                'created_at' => now()
            ]);
    }
}
