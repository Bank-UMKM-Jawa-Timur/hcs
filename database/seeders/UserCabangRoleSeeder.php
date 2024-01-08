<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserCabangRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cabang = \DB::table('users')
                ->select('id')
                ->whereNotNull('kd_cabang')
                ->get();

        \DB::beginTransaction();
        try {
            foreach ($cabang as $key => $value) {
                $role = [
                    'role_id' => 4,
                    'model_type' => 'App\Models\User',
                    'model_id' => $value->id,
                ];
                \DB::table('model_has_roles')->insert($role);
            }
            \DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
        }
    }
}
