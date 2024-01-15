<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilKantorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProfilKantorPusatSeeder::class,
            ProfilKantorCabangSeeder::class,
            PenambahBrutoSeeder::class,
            PengurangBrutoSeeder::class,
        ]);
    }
}
