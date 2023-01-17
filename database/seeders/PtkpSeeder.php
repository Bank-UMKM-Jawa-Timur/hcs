<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PtkpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('set_ptkp')
            ->insert([
                'kode' => 'K/0',
                'ptkp_tahun' => 58500000,
                'ptkp_bulan' => 4875000,
                'keterangan' => 'KAWIN TANPA ANAK',
                'created_at' => now()
            ]);
        DB::table('set_ptkp')
            ->insert([
                'kode' => 'K/1',
                'ptkp_tahun' =>  63000000,
                'ptkp_bulan' =>  5250000,
                'keterangan' => 'KAWIN + 1 ANAK',
                'created_at' => now()
            ]);
        DB::table('set_ptkp')
            ->insert([
                'kode' => 'K/2',
                'ptkp_tahun' =>  67500000,
                'ptkp_bulan' =>  5625000,
                'keterangan' => 'KAWIN + 2 ANAK',
                'created_at' => now()
            ]);
        DB::table('set_ptkp')
            ->insert([
                'kode' => 'K/3',
                'ptkp_tahun' =>  72000000,
                'ptkp_bulan' =>  6000000,
                'keterangan' => 'KAWIN + 3 ANAK',
                'created_at' => now()
            ]);
        DB::table('set_ptkp')
            ->insert([
                'kode' => 'TK',
                'ptkp_tahun' =>  54000000,
                'ptkp_bulan' =>  4500000,
                'keterangan' => 'TIDAK KAWIN',
                'created_at' => now()
            ]);
    }
}
