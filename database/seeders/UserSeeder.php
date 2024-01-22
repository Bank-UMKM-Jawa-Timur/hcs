<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = DB::table('users')
                    ->insertGetId([
                        'name' => 'Admin',
                        'username' => 'admin',
                        'email' => 'admin@mail.com',
                        'password' => Hash::make('12345678'),
                        'created_at' => now()
                    ]);
        DB::table('model_has_roles')
            ->insert([
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => $user_id,
            ]);
    }
}
