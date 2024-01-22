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
        $ptkp = [
            [
                'kode' => 'K/0',
                'ptkp_tahun' => 58500000,
                'ptkp_bulan' => 4875000,
                'keterangan' => 'KAWIN TANPA ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'K/1',
                'ptkp_tahun' =>  63000000,
                'ptkp_bulan' =>  5250000,
                'keterangan' => 'KAWIN + 1 ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'K/2',
                'ptkp_tahun' =>  67500000,
                'ptkp_bulan' =>  5625000,
                'keterangan' => 'KAWIN + 2 ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'K/3',
                'ptkp_tahun' =>  72000000,
                'ptkp_bulan' =>  6000000,
                'keterangan' => 'KAWIN + 3 ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'TK',
                'ptkp_tahun' =>  54000000,
                'ptkp_bulan' =>  4500000,
                'keterangan' => 'TIDAK KAWIN',
                'created_at' => now()
            ],
            [
                'kode' => 'TK/1',
                'ptkp_tahun' =>  58500000,
                'ptkp_bulan' =>  4875000,
                'keterangan' => 'TIDAK KAWIN + 1 ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'TK/2',
                'ptkp_tahun' =>  63000000,
                'ptkp_bulan' =>  5250000,
                'keterangan' => 'TIDAK KAWIN + 2 ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'TK/3',
                'ptkp_tahun' =>  67500000,
                'ptkp_bulan' =>  5625000,
                'keterangan' => 'TIDAK KAWIN + 3 ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'K/I/0',
                'ptkp_tahun' =>  112500000,
                'ptkp_bulan' =>  9375000,
                'keterangan' => 'KAWIN ISTRI USAHA TANPA ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'K/I/1',
                'ptkp_tahun' =>  117000000,
                'ptkp_bulan' =>  9750000,
                'keterangan' => 'KAWIN ISTRI USAHA + 1 ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'K/I/2',
                'ptkp_tahun' =>  121500000,
                'ptkp_bulan' =>  10125000,
                'keterangan' => 'KAWIN ISTRI USAHA + 2 ANAK',
                'created_at' => now()
            ],
            [
                'kode' => 'K/I/3',
                'ptkp_tahun' =>  126000000,
                'ptkp_bulan' =>  10500000,
                'keterangan' => 'KAWIN ISTRI USAHA + 3 ANAK',
                'created_at' => now()
            ],
        ];
        DB::table('set_ptkp')->insert($ptkp);
    }
}
