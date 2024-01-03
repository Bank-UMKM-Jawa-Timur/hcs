<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanPasswordSeeder extends Seeder
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
                DB::table('mst_karyawan')
                    ->where('nip', $value->nip)
                    ->update([
                        'password' => \Hash::make($value->nip),
                    ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
