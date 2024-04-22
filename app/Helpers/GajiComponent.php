<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use stdClass;

class GajiComponent
{
    /**
     * 1. Get Hitungan Bruto Function
     * 2. Get Hitungan JKK
     * 3. Get Hitungan JHT
     * 4. Get Hitungan JKM
     * 5. Get Hitungan Kesehatan
     * 6. Get Hitungan JP Penambah
     * 7. Get Penambah Bruto Jamsostek
     * 8. Get DPP
     * 9. Get BPJS TK
     */

    private $kd_entitas;
    private $cabang_arr;

    public function __construct($kd_entitas) {
        $this->kd_entitas = $kd_entitas;
        $this->cabang_arr = DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();
    }

    public function getMasterHitunganBruto() : stdClass {
        $hitungan = new stdClass;
        if (!$this->kd_entitas) {
            $penambah = DB::table('pemotong_pajak_tambahan')
                                    ->where('kd_cabang', '000')
                                    ->where('active', 1)
                                    ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                                    ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                                    ->first();
            $pengurang = DB::table('pemotong_pajak_pengurangan')
                                    ->where('kd_cabang', '000')
                                    ->where('active', 1)
                                    ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                                    ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                                    ->first();
        }
        else if (in_array($this->kd_entitas, $this->cabang_arr)) {
            $penambah = DB::table('pemotong_pajak_tambahan')
                                    ->where('kd_cabang', $this->kd_entitas)
                                    ->where('active', 1)
                                    ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                                    ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                                    ->first();
            $pengurang = DB::table('pemotong_pajak_pengurangan')
                                    ->where('kd_cabang', $this->kd_entitas)
                                    ->where('active', 1)
                                    ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                                    ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                                    ->first();
        } else {
            $penambah = DB::table('pemotong_pajak_tambahan')
                                    ->where('kd_cabang', '000')
                                    ->where('active', 1)
                                    ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                                    ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                                    ->first();
            $pengurang = DB::table('pemotong_pajak_pengurangan')
                                    ->where('kd_cabang', '000')
                                    ->where('active', 1)
                                    ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                                    ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                                    ->first();
        }

        $hitungan->penambah = $penambah;
        $hitungan->pengurang = $pengurang;

        return $hitungan;
    }

    // Penambah Bruto
    public function getJKK($kpj, $total_gaji, $is_floor=false) : int {
        $result = 0;

        if($total_gaji > 0){
            $hitungan = $this->getMasterHitunganBruto();
            $persen_jkk = 0;
            if ($hitungan) {
                $penambah = $hitungan->penambah;
                $persen_jkk = $penambah->jkk;
            }

            if($kpj){
                $result = $is_floor ? floor(($persen_jkk / 100) * $total_gaji) : round(($persen_jkk / 100) * $total_gaji);
            }
        }

        return $result;
    }

    public function getJHT($kpj, $total_gaji, $is_floor=false) : int {
        $result = 0;

        if($total_gaji > 0){
            $hitungan = $this->getMasterHitunganBruto();
            $persen_jht = 0;
            if ($hitungan) {
                $penambah = $hitungan->penambah;
                $persen_jht = $penambah->jht;
            }

            if($kpj){
                $result = $is_floor ? floor(($persen_jht / 100) * $total_gaji) : round(($persen_jht / 100) * $total_gaji);
            }
        }

        return $result;
    }

    public function getJKM($kpj, $total_gaji, $is_floor=false) : int {
        $result = 0;

        if($total_gaji > 0){
            $hitungan = $this->getMasterHitunganBruto();
            $persen_jkm = 0;
            if ($hitungan) {
                $penambah = $hitungan->penambah;
                $persen_jkm = $penambah->jkm;
            }

            if($kpj){
                $result = $is_floor ? floor(($persen_jkm / 100) * $total_gaji) : round(($persen_jkm / 100) * $total_gaji);
            }
        }

        return $result;
    }

    public function getKesehatan($jkn, $total_gaji, $is_floor=false) : int {
        $result = 0;

        if($total_gaji > 0){
            $hitungan = $this->getMasterHitunganBruto();
            $persen_kesehatan = 0;
            $batas_atas = 0;
            $batas_bawah = 0;
            if ($hitungan) {
                $penambah = $hitungan->penambah;
                $persen_kesehatan = $penambah->kesehatan;
                $batas_atas = $penambah->kesehatan_batas_atas;
                $batas_bawah = $penambah->kesehatan_batas_bawah;
            }
            if($jkn){
                if($total_gaji > $batas_atas){
                    $result = $is_floor ? floor($batas_atas * ($persen_kesehatan / 100)) : round($batas_atas * ($persen_kesehatan / 100));
                } else if($total_gaji < $batas_bawah){
                    $result = $is_floor ? floor($batas_bawah * ($persen_kesehatan / 100)) : round($batas_bawah * ($persen_kesehatan / 100));
                } else{
                    $result = $is_floor ? floor($total_gaji * ($persen_kesehatan / 100)) : round($total_gaji * ($persen_kesehatan / 100));
                }
            }
        }

        return $result;
    }

    public function getJPPenambah($kpj, $total_gaji, $is_floor=false) : int {
        $result = 0;

        if($total_gaji > 0){
            $hitungan = $this->getMasterHitunganBruto();
            $persen_jp_penambah = 0;
            if ($hitungan) {
                $penambah = $hitungan->penambah;
                $persen_jp_penambah = $penambah->jp;
            }
            if($kpj){
                $result = $is_floor ? floor(($persen_jp_penambah / 100) * $total_gaji) : round(($persen_jp_penambah / 100) * $total_gaji);
            }
        }

        return $result;
    }

    public function getPenambahBrutoJamsostek($kpj, $jkn, $total_gaji, $is_floor=false) : int {
        $result = 0;

        if($total_gaji > 0){
            $hitungan = $this->getMasterHitunganBruto();
            $persen_jkk = 0;
            $persen_jht = 0;
            $persen_jkm = 0;
            $persen_kesehatan = 0;
            $persen_jp_penambah = 0;
            $batas_atas = 0;
            $batas_bawah = 0;
            if ($hitungan) {
                $penambah = $hitungan->penambah;
                $persen_jkk = $penambah->jkk;
                $persen_jht = $penambah->jht;
                $persen_jkm = $penambah->jkm;
                $persen_kesehatan = $penambah->kesehatan;
                $persen_jp_penambah = $penambah->jp;
                $batas_atas = $penambah->kesehatan_batas_atas;
                $batas_bawah = $penambah->kesehatan_batas_bawah;
            }
            $jkk = 0;
            $jht = 0;
            $jkm = 0;
            $jp_penambah = 0;
            $kesehatan = 0;

            if($kpj){
                $jkk = $is_floor ? floor(($persen_jkk / 100) * $total_gaji) : round(($persen_jkk / 100) * $total_gaji);
                $jht = $is_floor ? floor(($persen_jht / 100) * $total_gaji) : round(($persen_jht / 100) * $total_gaji);
                $jkm = $is_floor ? floor(($persen_jkm / 100) * $total_gaji) : round(($persen_jkm / 100) * $total_gaji);
                $jp_penambah = $is_floor ? floor(($persen_jp_penambah / 100) * $total_gaji) : round(($persen_jp_penambah / 100) * $total_gaji);
            }

            if($jkn){
                if($total_gaji > $batas_atas){
                    $kesehatan = $is_floor ? floor($batas_atas * ($persen_kesehatan / 100)) : round($batas_atas * ($persen_kesehatan / 100));
                } else if($total_gaji < $batas_bawah){
                    $kesehatan = $is_floor ? floor($batas_bawah * ($persen_kesehatan / 100)) : round($batas_bawah * ($persen_kesehatan / 100));
                } else{
                    $kesehatan = $is_floor ? floor($total_gaji * ($persen_kesehatan / 100)) : round($total_gaji * ($persen_kesehatan / 100));
                }
            }
            $result = $jkk + $jht + $jkm + $kesehatan + $jp_penambah;
        }

        return $result;
    }
    // END Penambah Bruto

    // Pengurang Bruto
    public function getDPP($status_karyawan, $gaji_pokok, $tj_keluarga, $tj_kesejahteraan, $is_floor=false) : int {
        $result = 0;

        if($status_karyawan == 'IKJP' || $status_karyawan == 'Kontrak Perpanjangan') {
            // $result = ($persen_jp_pengurang / 100) * $total_gaji;
        }
        else {
            $result = $is_floor ? floor(((($gaji_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * 0.05)) : round(((($gaji_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * 0.05));
        }

        return $result;
    }

    public function getBPJSTK($kpj, int $total_gaji, int $bulan, $is_floor=false, $two_percent=false) : int {
        $result = 0;
        // Get Master Hitungan Bruto
        $jp_pengurang = 0;
        $jp_mar_des = 0;
        $jp_jan_feb = 0;
        $hitungan = $this->getMasterHitunganBruto();
        if ($hitungan) {
            $pengurang = $hitungan->pengurang;
            $jp_pengurang = $pengurang->jp;
            $jp_jan_feb = $pengurang->jp_jan_feb;
            $jp_mar_des = $pengurang->jp_mar_des;
        }
        // END Get Master Hitungan Bruto

        if($kpj) {
            $jp_persen = $two_percent ? 2 / 100 : $jp_pengurang / 100;
            $bpjs_tk = 0;
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
            $result = $is_floor ? floor($bpjs_tk) : round($bpjs_tk);
        }

        return $result;
    }
    // END Pengurang Bruto
}
