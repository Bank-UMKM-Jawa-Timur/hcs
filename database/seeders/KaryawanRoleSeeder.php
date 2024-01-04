<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $karyawan = DB::table('mst_karyawan')
                            ->select('nip')
                            ->get();
            foreach ($karyawan as $value) {
                DB::table('model_has_roles')
                    ->insert([
                        'role_id' => 5,
                        'model_type' => 'App\Models\KaryawanModel',
                        'model_id' => $value->nip,
                    ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
