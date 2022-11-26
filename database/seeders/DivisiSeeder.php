<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_divisi')
            ->insert([
                'kd_divisi' => 'RPK',
                'nama_divisi' => 'Risiko & Penyelamatan Kredit',
                'id_kantor' => 1,
                'created_at' => now()
            ]);
        DB::table('mst_divisi')
            ->insert([
                'kd_divisi' => 'PSN',
                'nama_divisi' => 'Pemasaran',
                'id_kantor' => 1,
                'created_at' => now()
            ]);
        DB::table('mst_divisi')
            ->insert([
                'kd_divisi' => 'UMUM',
                'nama_divisi' => 'Umum',
                'id_kantor' => 1,
                'created_at' => now()
            ]);
        DB::table('mst_divisi')
            ->insert([
                'kd_divisi' => 'TIAK',
                'nama_divisi' => 'Teknologi Informasi & Akuntansi',
                'id_kantor' => 1,
                'created_at' => now()
            ]);
        DB::table('mst_divisi')
            ->insert([
                'kd_divisi' => 'KPH',
                'nama_divisi' => 'Kepatuhan',
                'id_kantor' => 1,
                'created_at' => now()
            ]);
        DB::table('mst_divisi')
            ->insert([
                'kd_divisi' => 'PI',
                'nama_divisi' => 'Pengawas Intern',
                'id_kantor' => 1,
                'created_at' => now()
            ]);
        DB::table('mst_divisi')
            ->insert([
                'kd_divisi' => 'PK',
                'nama_divisi' => 'Perencanaan & Kinerja',
                'id_kantor' => 1,
                'created_at' => now()
            ]);
    }
}
