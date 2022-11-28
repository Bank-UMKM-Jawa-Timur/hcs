<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PanggolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Pegawai Dasar Muda',
                'golongan' => 'A.1',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Pegawai Dasar Muda 1',
                'golongan' => 'A.2',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Pegawai Dasar',
                'golongan' => 'A.3',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => '	Pegawai Dasar 1',
                'golongan' => 'A.4',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Pelaksana Muda',
                'golongan' => 'B.1',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Pelaksana Muda 1',
                'golongan' => 'B.2',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Pelaksana',
                'golongan' => 'B.3',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Pelaksana 1',
                'golongan' => 'B.4',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Staf Muda',
                'golongan' => 'C.1',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Staf Muda 1',
                'golongan' => 'C.2',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Staf',
                'golongan' => 'C.3',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Staf 1',
                'golongan' => 'C.4',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Staf Madya',
                'golongan' => 'D.1',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Staf Madya 1',
                'golongan' => 'D.2',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Staf Madya Utama',
                'golongan' => 'D.3',
                'created_at' => now()
            ]);
        DB::table('mst_pangkat_golongan')
            ->insert([
                'pangkat' => 'Staf Utama',
                'golongan' => 'D.4',
                'created_at' => now()
            ]);
    }
}
