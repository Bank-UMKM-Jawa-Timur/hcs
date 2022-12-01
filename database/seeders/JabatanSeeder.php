<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_jabatan')
        ->insert([
            'kd_jabatan' => 'PBO',
            'nama_jabatan' => 'Pemimpin Bidang Operasional',
            'created_at' => now()
        ]);
        DB::table('mst_jabatan')
        ->insert([
            'kd_jabatan' => 'PBP',
            'nama_jabatan' => 'Pemimpin Bidang Pemasaran',
            'created_at' => now()
        ]);
        DB::table('mst_jabatan')
        ->insert([
            'kd_jabatan' => 'PC',
            'nama_jabatan' => 'Pemimpin Cabang',
            'created_at' => now()
        ]);
        DB::table('mst_jabatan')
        ->insert([
            'kd_jabatan' => 'PEN',
            'nama_jabatan' => 'Penyelia',
            'created_at' => now()
        ]);
        DB::table('mst_jabatan')
        ->insert([
            'kd_jabatan' => 'PIMDIV',
            'nama_jabatan' => 'Pemimpin Divisi',
            'created_at' => now()
        ]);
        DB::table('mst_jabatan')
        ->insert([
            'kd_jabatan' => 'PSD',
            'nama_jabatan' => 'Pemimpin Sub Divisi',
            'created_at' => now()
        ]);
        DB::table('mst_jabatan')
        ->insert([
            'kd_jabatan' => 'ST',
            'nama_jabatan' => 'Staf',
            'created_at' => now()
        ]);
        DB::table('mst_jabatan')
        ->insert([
            'kd_jabatan' => 'NST',
            'nama_jabatan' => 'Non Staf',
            'created_at' => now()
        ]);
        DB::table('mst_jabatan')
        ->insert([
            'kd_jabatan' => 'IKJP',
            'nama_jabatan' => 'IKJP',
            'created_at' => now()
        ]);
    }
}
