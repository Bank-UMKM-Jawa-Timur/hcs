<?php

namespace Database\Seeders;

use App\Repository\CabangRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePotonganGajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list_gaji = DB::table('gaji_per_bulan AS gaji')
                        ->select(
                            'm.*',
                            'gaji.id AS gaji_id',
                            DB::raw('CAST(gaji.bulan AS UNSIGNED) AS bulan'),
                            DB::raw('CAST(gaji.gj_pokok AS SIGNED) AS gj_pokok'),
                            DB::raw('CAST(gaji.tj_keluarga AS SIGNED) AS tj_keluarga'),
                            DB::raw('CAST(gaji.tj_kesejahteraan AS SIGNED) AS tj_kesejahteraan'),
                            DB::raw("(gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_telepon + gaji.tj_pelaksana + gaji.tj_kemahalan + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) AS total_gaji"),
                        )
                        ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                        ->orderBy('id')
                        ->get();
        $cabangRepo = new CabangRepository;
        $cabang = $cabangRepo->listCabang(true);

        DB::beginTransaction();
        try {
            foreach ($list_gaji as $item) {
                // Get penambah & pengurang bruto
                if (!$item->kd_entitas) {
                    $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                                            ->where('kd_cabang', '000')
                                            ->where('active', 1)
                                            ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                                            ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                                            ->first();
                    $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                                            ->where('kd_cabang', '000')
                                            ->where('active', 1)
                                            ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                                            ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                                            ->first();
                }
                else if (in_array($item->kd_entitas, $cabang)) {
                    $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                                            ->where('kd_cabang', $item->kd_entitas)
                                            ->where('active', 1)
                                            ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                                            ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                                            ->first();
                    $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                                            ->where('kd_cabang', $item->kd_entitas)
                                            ->where('active', 1)
                                            ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                                            ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                                            ->first();
                } else {
                    $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                                            ->where('kd_cabang', '000')
                                            ->where('active', 1)
                                            ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                                            ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                                            ->first();
                    $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                                            ->where('kd_cabang', '000')
                                            ->where('active', 1)
                                            ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                                            ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                                            ->first();
                }
    
                if (!$hitungan_penambah && !$hitungan_pengurang) {
                    $persen_jkk = 0;
                    $persen_jht = 0;
                    $persen_jkm = 0;
                    $persen_kesehatan = 0;
                    $persen_jp_penambah = 0;
                    $persen_dpp = 0;
                    $persen_jp_pengurang = 0;
                    $batas_atas = 0;
                    $batas_bawah = 0;
                    $jp_jan_feb = 0;
                    $jp_mar_des = 0;
                } else{
                    $persen_jkk = $hitungan_penambah->jkk;
                    $persen_jht = $hitungan_penambah->jht;
                    $persen_jkm = $hitungan_penambah->jkm;
                    $persen_kesehatan = $hitungan_penambah->kesehatan;
                    $persen_jp_penambah = $hitungan_penambah->jp;
                    $persen_dpp = $hitungan_pengurang->dpp;
                    $persen_jp_pengurang = $hitungan_pengurang->jp;
                    $batas_atas = $hitungan_penambah->kesehatan_batas_atas;
                    $batas_bawah = $hitungan_penambah->kesehatan_batas_bawah;
                    $jp_jan_feb = $hitungan_pengurang->jp_jan_feb;
                    $jp_mar_des = $hitungan_pengurang->jp_mar_des;
                }
    
                $bulan = $item->bulan;
                $gj_pokok = $item->gj_pokok;
                $tj_keluarga = $item->tj_keluarga;
                $tj_kesejahteraan = $item->tj_kesejahteraan;
                $total_gaji = $item->total_gaji;
    
                $dpp = 0;
                $jp_1_persen = 0;
                $nominal_jp = ($bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                if($item->status_karyawan == 'IKJP' || $item->status_karyawan == 'Kontrak Perpanjangan') {
                    $dpp = ($persen_jp_pengurang / 100) * $total_gaji;
                }
                else {
                    // Get DPP
                    $dpp = floor(((($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * 0.05));
                    // Get JP 1%
                    $jp_1_persen = floor($total_gaji * ($persen_jp_pengurang / 100));
                    if($total_gaji >= $nominal_jp){
                        $jp_1_persen = floor($nominal_jp * ($persen_jp_pengurang / 100));
                    } else {
                        $jp_1_persen = floor($total_gaji * ($persen_jp_pengurang / 100));
                    }
                }

                // Get BPJS TK
                $jp_persen = $persen_jp_pengurang / 100;
                if ($bulan > 2) {
                    if ($total_gaji > $jp_mar_des) {
                        $bpjs_tk = $jp_mar_des * $jp_persen;
                    }
                    else {
                        $bpjs_tk = $total_gaji * $jp_persen;
                    }
                }
                else {
                    if ($total_gaji >= $jp_jan_feb) {
                        $bpjs_tk = $jp_jan_feb * $jp_persen;
                    }
                    else {
                        $bpjs_tk = $total_gaji * $jp_persen;
                    }
                }
                $bpjs_tk = floor($bpjs_tk);

                // Get Penambah Bruto Jamsostek
                $jamsostek = 0;
                if($total_gaji > 0){
                    $jkk = 0;
                    $jht = 0;
                    $jkm = 0;
                    $jp_penambah = 0;
                    if(!$item->tanggal_penonaktifan && $item->kpj){
                        $jkk = floor(($persen_jkk / 100) * $total_gaji);
                        $jht = floor(($persen_jht / 100) * $total_gaji);
                        $jkm = floor(($persen_jkm / 100) * $total_gaji);
                        $jp_penambah = floor(($persen_jp_penambah / 100) * $total_gaji);
                    }
    
                    if($item->jkn){
                        if($total_gaji > $batas_atas){
                            $bpjs_kesehatan = floor($batas_atas * ($persen_kesehatan / 100));
                        } else if($total_gaji < $batas_bawah){
                            $bpjs_kesehatan = floor($batas_bawah * ($persen_kesehatan / 100));
                        } else{
                            $bpjs_kesehatan = floor($total_gaji * ($persen_kesehatan / 100));
                        }
                    }
                    $jamsostek = $jkk + $jht + $jkm + $bpjs_kesehatan + $jp_penambah;
                }
    
                DB::table('gaji_per_bulan')
                    ->where('id', $item->gaji_id)
                    ->update([
                        'dpp' => $dpp,
                        'bpjs_tk' => $bpjs_tk,
                        'jp' => $jp_1_persen,
                        'penambah_bruto_jamsostek' => $jamsostek,
                    ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
        }
    }
}
