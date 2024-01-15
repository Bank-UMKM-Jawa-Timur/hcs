<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilKantorCabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cabang = DB::table('mst_cabang')
                    ->where('kd_cabang', '!=', '000')
                    ->orderBy('kd_cabang')
                    ->pluck('kd_cabang')
                    ->toArray();
        $email = config('global.email_cabang');
        $npwp_pemotong = [
            '019419159631000',
            '019419159608001',
            '019419159644001',
            '019419159627001',
            '019419159657002',
            '019419159625001',
            '019419159624001',
            '019419159651000',
            '019419159646002',
            '019419159653002',
            '019419159622002',
            '019419159602002',
            '019419159602001',
            '019419159655005',
            '019419159621001',
            '019419159629001',
            '019419159629003',
            '019419159647004',
            '019419159612001',
            '019419159645001',
            '019419159621003',
            '019419159647005',
            '019419159617001',
            '019419159626001',
            '019419159648001',
            '019419159628001',
            '019419159656001',
            '019419159601001',
            '019419159656002',
            '019419159608003',
            '019419159654001',
            '019419159644002'
        ];
        for ($i=0; $i < count($cabang); $i++) { 
            DB::table('mst_profil_kantor')->insert([
                'kd_cabang' => $cabang[$i],
                'masa_pajak' => '07-2023',
                'tanggal_lapor' => '2023-01-20',
                'npwp_pemotong' => $npwp_pemotong[$i],
                'nama_pemotong' => 'PT. BPR JATIM',
                'telp' => '0315677844',
                'email' => $email[$i],
                'npwp_pemimpin_cabang' => '247504327618000',
                'nama_pemimpin_cabang' => 'AGUNG SOEPRIHATMANTO',
            ]);
        }
    }
}
