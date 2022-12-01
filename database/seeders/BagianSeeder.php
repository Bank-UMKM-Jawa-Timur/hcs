<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BagianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-RKT',
            'kd_entitas' => 'RK',
            'nama_bagian' => 'Risiko Kredit',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PKB',
            'kd_entitas' => 'RK',
            'nama_bagian' => 'Penyelamatan Kredit Bermasalah',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-HKB',
            'kd_entitas' => 'RK',
            'nama_bagian' => 'Hukum Kredit Bermasalah',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-ALMA',
            'kd_entitas' => 'TS',
            'nama_bagian' => 'ALMA',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-BNS',
            'kd_entitas' => 'TS',
            'nama_bagian' => 'Bisnis',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-SO',
            'kd_entitas' => 'TI',
            'nama_bagian' => 'Service & Operation',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-DEV',
            'kd_entitas' => 'TI',
            'nama_bagian' => 'Development',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PW1',
            'kd_entitas' => 'PW1',
            'nama_bagian' => 'Pengawasan Wilayah I',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-UMM',
            'kd_entitas' => 'UMUM',
            'nama_bagian' => 'Umum',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PBJ',
            'kd_entitas' => 'UMUM',
            'nama_bagian' => 'Pengadaan Barang & Jasa',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PNM',
            'kd_entitas' => 'UMUM',
            'nama_bagian' => 'Pengemudi',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PMB',
            'kd_entitas' => 'UMUM',
            'nama_bagian' => 'Pramubakti',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-STP',
            'kd_entitas' => 'UMUM',
            'nama_bagian' => 'Satpam',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KU',
            'kd_entitas' => 'KU',
            'nama_bagian' => 'Kredit Umum',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-AKR',
            'kd_entitas' => 'KU',
            'nama_bagian' => 'Admin Kredit',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PR',
            'kd_entitas' => 'CSR',
            'nama_bagian' => 'Public Relation',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KST',
            'kd_entitas' => 'CSR',
            'nama_bagian' => 'Kesekretariatan',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-AK',
            'kd_entitas' => 'AK',
            'nama_bagian' => 'Akuntansi',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PW2',
            'kd_entitas' => 'PW2',
            'nama_bagian' => 'Pengawasan Wilayah II',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KP',
            'kd_entitas' => 'KP',
            'nama_bagian' => 'Kredit Program',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-HRD',
            'kd_entitas' => 'SDM',
            'nama_bagian' => 'HRD',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KPW',
            'kd_entitas' => 'SDM',
            'nama_bagian' => 'Kepegawaian',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KPH',
            'kd_entitas' => 'KAP',
            'nama_bagian' => 'Kepatuhan',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-APPPT',
            'kd_entitas' => 'KAP',
            'nama_bagian' => 'APU-PPT',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-SP',
            'kd_entitas' => 'KAP',
            'nama_bagian' => 'Service & Prosedur',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PRC',
            'kd_entitas' => 'PKJ',
            'nama_bagian' => 'Perencanaan',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KNJ',
            'kd_entitas' => 'PKJ',
            'nama_bagian' => 'Kinerja',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KKR',
            'kd_entitas' => 'MK',
            'nama_bagian' => 'Kredit Kualitas Rendah',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-AP',
            'kd_entitas' => 'PK',
            'nama_bagian' => 'Assessment & Pengembangan',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PRS',
            'kd_entitas' => 'PR',
            'nama_bagian' => 'Pengendalian Risiko',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-HKM',
            'kd_entitas' => 'PR',
            'nama_bagian' => 'Hukum',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PPK',
            'kd_entitas' => 'PPK',
            'nama_bagian' => 'Pengembangan Produk & Kebijakan',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PI',
            'kd_entitas' => 'PI',
            'nama_bagian' => 'Pengawasan Intern',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PIC',
            'kd_entitas' => 'PI',
            'nama_bagian' => 'Pengawasan Intern Cabang',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-TK',
            'kd_entitas' => 'UMUM',
            'nama_bagian' => 'Teknisi',
            'created_at' => now()
        ]);
        // Bagian ke mst_kantor
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KRW',
            'kd_entitas' => 2,
            'nama_bagian' => 'Kredit Wilayah',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KRT',
            'kd_entitas' => 2,
            'nama_bagian' => 'Kredit Retail',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KTS',
            'kd_entitas' => 2,
            'nama_bagian' => 'Kredit Support',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-KRD',
            'kd_entitas' => 2,
            'nama_bagian' => 'Kredit',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-UMUM',
            'kd_entitas' => 2,
            'nama_bagian' => 'Umum',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-DPN',
            'kd_entitas' => 2,
            'nama_bagian' => 'Dana & Pelayanan Nasabah',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-AKT',
            'kd_entitas' => 2,
            'nama_bagian' => 'Akuntansi',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-TLR',
            'kd_entitas' => 2,
            'nama_bagian' => 'Teller',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-UMAK',
            'kd_entitas' => 2,
            'nama_bagian' => 'Umum & Akuntansi',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PJM',
            'kd_entitas' => 2,
            'nama_bagian' => 'Penjaga Malam',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PRB',
            'kd_entitas' => 2,
            'nama_bagian' => 'Pramubakti',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PMD',
            'kd_entitas' => 2,
            'nama_bagian' => 'Pengemudi',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-STPM',
            'kd_entitas' => 2,
            'nama_bagian' => 'Satpam',
            'created_at' => now()
        ]);
        DB::table('mst_bagian')
        ->insert([
            'kd_bagian' => 'B-PGW',
            'kd_entitas' => 2,
            'nama_bagian' => 'Pegawai',
            'created_at' => now()
        ]);
    }
}
