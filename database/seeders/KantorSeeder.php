<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KantorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('mst_kantor')->insert([
            'nama_kantor' => 'Kantor Pusat',
            'created_at' => now()
        ]);

        DB::table('mst_kantor')->insert([
            'nama_kantor' => 'Kantor Cabang',
            'created_at' => now()
        ]);
    }
}
