<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorePenghasilanTidakTeraturToBatchSeeder extends Seeder
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
            $batch_id = DB::table('batch_penghasilan_tidak_teratur')
                            ->pluck('penghasilan_tidak_teratur_id')
                            ->toArray();
            DB::table('penghasilan_tidak_teratur AS p')
                ->select(
                    'p.*',
                    'gaji_per_bulan.id AS gaji_id',
                )
                ->join('gaji_per_bulan', function ($join) {
                    $join->on('gaji_per_bulan.nip', 'p.nip')
                        ->on('gaji_per_bulan.tahun', 'p.tahun')
                        ->on('gaji_per_bulan.bulan', 'p.bulan');
                })
                ->whereNotIn('p.id', $batch_id)
                ->orderBy('p.id')
                ->chunk(100, function ($items) {
                    $now = now();
                    foreach ($items as $item) {
                        $batch_tidak_rutin = [
                            'gaji_per_bulan_id' => $item->gaji_id,
                            'penghasilan_tidak_teratur_id' => $item->id,
                            'id_tunjangan' => $item->id_tunjangan,
                            'nominal' => $item->nominal,
                            'created_at' => $now,
                        ];
                        DB::table('batch_penghasilan_tidak_teratur')
                            ->insert($batch_tidak_rutin);
                    }
                });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
