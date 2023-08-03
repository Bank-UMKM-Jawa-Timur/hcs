<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
            KantorSeeder::class,
            DivisiSeeder::class,
            CabangSeeder::class,
            SubDivisiSeeder::class,
            JabatanSeeder::class,
            BagianSeeder::class,
            PanggolSeeder::class,
            AgamaSeeder::class,
            TunjanganSeeder::class,
            KantorPusatSeeder::class,
        ]);
    }
}
