<?php

namespace Database\Seeders;

use App\Helpers\GajiComponent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePemotongPenambahOnGajiPerbulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gaji = DB::table('gaji_per_bulan AS gaji')
                    ->select(
                        'karyawan.nip',
                        'karyawan.kd_entitas',
                        'karyawan.status_karyawan',
                        'karyawan.jkn',
                        'karyawan.kpj',
                        'gaji.id',
                        'gaji.bulan',
                        'gaji.gj_pokok',
                        'gaji.tj_keluarga',
                        'gaji.tj_kesejahteraan',
                        DB::raw("(gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_telepon + gaji.tj_pelaksana + gaji.tj_kemahalan + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) AS total_gaji"),
                    )
                    ->join('mst_karyawan AS karyawan', 'karyawan.nip', 'gaji.nip')
                    ->get();
        DB::beginTransaction();
        try {
            foreach ($gaji as $key => $value) {
                $gaji_component = new GajiComponent($value->kd_entitas);
                $kpj = $value->kpj;
                $jkn = $value->jkn;
                $total_gaji = $value->total_gaji;

                // Penambah
                $jkk = $gaji_component->getJKK($kpj, $total_gaji, true);
                $jht = $gaji_component->getJHT($kpj, $total_gaji, true);
                $jkm = $gaji_component->getJKM($kpj, $total_gaji, true);
                $kesehatan = $gaji_component->getKesehatan($jkn, $total_gaji, true);
                $jp = $gaji_component->getJPPenambah($kpj, $total_gaji, true);
                $data = [
                    'jkk' => $jkk,
                    'jht' => $jht,
                    'jkm' => $jkm,
                    'kesehatan' => $kesehatan,
                    'jp' => $jp,
                ];
                DB::table('gaji_per_bulan')
                    ->where('id', $value->id)
                    ->update($data);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
