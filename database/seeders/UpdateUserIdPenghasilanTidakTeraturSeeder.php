<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateUserIdPenghasilanTidakTeraturSeeder extends Seeder
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
            $data = DB::table('penghasilan_tidak_teratur')->groupBy('kd_entitas')->get();
            foreach ($data as $value) {
                $user = DB::table('users')
                        ->where('kd_cabang', $value->kd_entitas)
                        ->first();
                $user_id = null;
                if ($user) {
                    $user_id = $user->id;
                }
                DB::table('penghasilan_tidak_teratur')
                    ->where('kd_entitas', $value->kd_entitas)
                    ->update([
                        'user_id' => $user_id
                    ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
        }
    }
}
