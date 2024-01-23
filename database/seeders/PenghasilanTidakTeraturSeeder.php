<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenghasilanTidakTeraturSeeder extends Seeder
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
                'nama_tunjangan' => 'Uang Lembur',
                'status' => 2,
                'created_at' => now()
            ]);
            
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Pengganti Biaya Kesehatan',
            'status' => 2,
            'created_at' => now()
        ]);
        
        DB::table('mst_tunjangan')
            ->insert([
                'nama_tunjangan' => 'Uang Duka',
                'status' => 2,
                'created_at' => now()
            ]);
            
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'SPD',
            'status' => 2,
            'created_at' => now()
        ]);
        
        DB::table('mst_tunjangan')
            ->insert([
                'nama_tunjangan' => 'SPD Pendidikan',
                'status' => 2,
                'created_at' => now()
            ]);
            
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'SPD Pindah Tugas',
            'status' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Insentif Kredit',
            'status' => 2,
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Insentif Penagihan',
            'status' => 2,
            'created_at' => now()
        ]);
    }
}
