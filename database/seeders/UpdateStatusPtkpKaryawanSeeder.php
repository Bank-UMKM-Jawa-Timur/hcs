<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateStatusPtkpKaryawanSeeder extends Seeder
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
                        ->select('id', 'nip', 'status_ptkp')
                        ->where('status_ptkp', 'TK')
                        ->get();
            foreach ($karyawan as $value) {
                DB::table('mst_karyawan')
                    ->where('id', $value->id)
                    ->update([
                        'status_ptkp' => 'TK/0'
                    ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
