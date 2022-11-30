<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TunjanganSeeder extends Seeder
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
            'nama_tunjangan' => 'Keluarga',
            'status' => '1',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Telpon, Air dan Listrik',
            'status' => '1',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Jabatan',
            'status' => '1',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Teller',
            'status' => '1',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Perumahan',
            'status' => '1',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Kemahalan',
            'status' => '1',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Pelaksana',
            'status' => '1',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Kesejahteraan',
            'status' => '1',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Multi Level Entry',
            'status' => '1',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'TI',
            'status' => '2',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Transport',
            'status' => '2',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Pulsa',
            'status' => '2',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Vitamin',
            'status' => '2',
            'created_at' => now()
        ]);
        DB::table('mst_tunjangan')
        ->insert([
            'nama_tunjangan' => 'Uang Makan',
            'status' => '2',
            'created_at' => now()
        ]);
    }
}
