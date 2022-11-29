<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubDivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'RK',
            'nama_subdivisi' => 'Risiko Kredit',
            'kd_divisi' => 'RPK',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'GPKB',
            'nama_subdivisi' => 'Group Penyelamatan Kredit Bermasalah',
            'kd_divisi' => 'RPK',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'KU',
            'nama_subdivisi' => 'Kredit Umum',
            'kd_divisi' => 'PMS',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'KP',
            'nama_subdivisi' => 'Kredit Program',
            'kd_divisi' => 'PMS',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'MK',
            'nama_subdivisi' => 'Monitoring Kredit',
            'kd_divisi' => 'PMS',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'TS',
            'nama_subdivisi' => 'Treasury',
            'kd_divisi' => 'PMS',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'GPKKR',
            'nama_subdivisi' => 'Group Penyelamatan Kredit Kualitas Rendah',
            'kd_divisi' => 'PMS',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'UMUM',
            'nama_subdivisi' => 'Umum',
            'kd_divisi' => 'UMUM',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'SDM',
            'nama_subdivisi' => 'SDM',
            'kd_divisi' => 'UMUM',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'PK',
            'nama_subdivisi' => 'Pengembagan Karir',
            'kd_divisi' => 'UMUM',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'TI',
            'nama_subdivisi' => 'Teknologi Informasi',
            'kd_divisi' => 'TIAK',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'AK',
            'nama_subdivisi' => 'Akuntansi',
            'kd_divisi' => 'TIAK',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'KAPK',
            'nama_subdivisi' => 'Kepatuhan & APU-PPTAkuntansi',
            'kd_divisi' => 'KPH',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'PR',
            'nama_subdivisi' => 'Pengendalian Risiko',
            'kd_divisi' => 'KPH',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'PW1',
            'nama_subdivisi' => 'Pengawasan Wilayah I',
            'kd_divisi' => 'PI',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'PW2',
            'nama_subdivisi' => 'Pengawasan Wilayah II',
            'kd_divisi' => 'PI',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'PKJ',
            'nama_subdivisi' => 'Perencanaan & Kinerja',
            'kd_divisi' => 'PK',
            'created_at' => now()
        ]);
        DB::table('mst_sub_divisi')
        ->insert([
            'kd_subdiv' => 'PPK',
            'nama_subdivisi' => 'Pengembangan Produk & Kebijakan',
            'kd_divisi' => 'PK',
            'created_at' => now()
        ]);
    }
}
