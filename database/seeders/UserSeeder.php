<?php

namespace Database\Seeders;

use App\Models\User;
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
        // DB::table('users')
        //     ->insert([
        //         'name' => 'Admin',
        //         'username' => 'admin',
        //         'email' => 'admin@mail.com',
        //         'password' => Hash::make('12345678'),
        //         'created_at' => now()
        //     ]);
        $user = new User;
        $user->name = 'Admin';
        $user->email  = 'admin@mail.com';
        $user->password = Hash::make('12345678');
        $user->save();
        $user->assignRole('admin');

    }
}
