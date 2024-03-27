<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateTHRPusatMaretSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //SELECT id, nip FROM `gaji_per_bulan` where batch_id = 129 and id not in(select gaji_per_bulan_id from batch_penghasilan_tidak_teratur where id_tunjangan = 22 and month(created_at) = 3 and year(created_at) = '2024');
        DB::beginTransaction();
        try {
            $batch_penghasilan_tidak_teratur = DB::table('batch_penghasilan_tidak_teratur')
                ->where('id_tunjangan', 22)
                ->whereMonth('created_at', 3)
                ->whereYear('created_at', 2024)
                ->pluck('gaji_per_bulan_id')
                ->toArray();
            $data = DB::table('gaji_per_bulan')
                ->select('id', 'nip')
                ->where('batch_id', 129)
                ->whereNotIn('id', $batch_penghasilan_tidak_teratur)
                ->get();

            foreach ($data as $item) {
                $thr = DB::table('penghasilan_tidak_teratur')
                    ->select('id', 'nominal')
                    ->where('id_tunjangan', 22)
                    ->where('nip', $item->nip)
                    ->whereMonth('created_at', 3)
                    ->whereYear('created_at', 2024)
                    ->get();

                foreach ($thr as $t) {
                    $id = $t->id;
                    $nominal = $t->nominal;
                    $insert = [
                        'gaji_per_bulan_id' => $item->id,
                        'penghasilan_tidak_teratur_id' => $id,
                        'id_tunjangan' => 22,
                        'nominal' => $nominal,
                        'created_at' => '2024-03-25 00:00:00'
                    ];
                    DB::table('batch_penghasilan_tidak_teratur')
                        ->insert($insert);
                }
            }
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
