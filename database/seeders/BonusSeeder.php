<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_tunjangan')
            ->insert([
                'nama_tunjangan' => 'Tunjangan Hari Raya',
                'status' => 2,
                'created_at' => now()
            ]);
            
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Jasa Produksi',
            'status' => 2,
            'created_at' => now()
        ]);
        
        DB::table('mst_tunjangan')
            ->insert([
                'nama_tunjangan' => 'Dana Pendidikan',
                'status' => 2,
                'created_at' => now()
            ]);
    }
}
