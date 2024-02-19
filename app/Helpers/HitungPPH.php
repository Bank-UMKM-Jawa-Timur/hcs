<?php

namespace App\Helpers;

use App\Repository\CabangRepository;
use Illuminate\Support\Facades\DB;

class HitungPPH
{
    public static function getPPh58($bulan, $tahun, $karyawan, $ptkp, $tanggal, $total_gaji, $tunjangan_rutin = 0, $full_month = false) {
        $tanggal = date('Y-m-d', strtotime($tanggal));
        $penghasilanRutin = 0;
        $penghasilanTidakRutin = 0;
        $penghasilanBruto = 0;

        // Get total penghasilan rutin
        $penghasilanRutin = $total_gaji;

        // Get Kode entitas
        $kd_entitas = '000';
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);
        if ($karyawan->kd_entitas) {
            if (in_array($karyawan->kd_entitas, $kode_cabang_arr)) {
                $kd_entitas = $karyawan->kd_entitas;
            }
        }

        // Get total penghasilan tidak rutin
        if ($bulan > 1) {
            if ($full_month) {
                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->where('tahun', (int) $tahun)
                                            ->where('bulan', (int) $bulan)
                                            ->sum('nominal');
            }
            else {
                // Penggajian bulan sebelumnya
                $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);

                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereBetween('created_at', [$start_date, $tanggal])
                                            ->sum('nominal');
            }
        }
        else if ($bulan == 12) {
            if ($full_month) {
                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->where('tahun', (int) $tahun)
                                            ->where('bulan', (int) $bulan)
                                            ->sum('nominal');
            }
            else {
                // Penggajian bulan sebelumnya
                $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                $currentMonth = intval(date('m', strtotime($tanggal)));
                $currentYear = date('Y', strtotime($tanggal));
                $last_day = getLastDateOfMonth($currentYear, $currentMonth);
                $end_date = $currentYear.'-'.$currentMonth.'-'.$last_day;

                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereBetween('created_at', [$start_date, $end_date])
                                            ->sum('nominal');
            }
        }
        else {
            if ($full_month) {
                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->where('tahun', (int) $tahun)
                                            ->where('bulan', (int) $bulan)
                                            ->sum('nominal');
            }
            else {
                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->where('tahun', (int) $tahun)
                                            ->where('bulan', (int) $bulan)
                                            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($tanggal)))
                                            ->sum('nominal');
            }
        }

        $jamsostek = HitungPPH::getJamsostek($karyawan, $total_gaji);

        $penghasilanBruto = $penghasilanRutin + $penghasilanTidakRutin + $jamsostek + $tunjangan_rutin;

        $pph = 0;

        $kode_ptkp = $ptkp->kode;
        $ter_kategori = HitungPPH::getTarifEfektifKategori($kode_ptkp);
        $lapisanPenghasilanBruto = DB::table('lapisan_penghasilan_bruto')
                                    ->where('kategori', $ter_kategori)
                                    ->where(function($query) use ($penghasilanBruto) {
                                        $query->where(function($q2) use ($penghasilanBruto) {
                                            $q2->where('nominal_start', '<=', $penghasilanBruto)
                                                ->where('nominal_end', '>=', $penghasilanBruto);
                                        })->orWhere(function($q2) use ($penghasilanBruto) {
                                            $q2->where('nominal_start', '<=', $penghasilanBruto)
                                                ->where('nominal_end', 0);
                                        });
                                    })
                                    ->first();
        $pengali = 0;
        if ($lapisanPenghasilanBruto) {
            $pengali = $lapisanPenghasilanBruto->pengali;
        }

        $pph = $penghasilanBruto * ($pengali / 100);
        $pph = floor($pph);

        return $pph;
    }

    public static function getDatePenggajianSebelumnya($tanggal_penggajian, $kd_entitas) {
        $currentMonth = intval(date('m', strtotime($tanggal_penggajian)));
        $beforeMonth = $currentMonth - 1;
        $currentYear = date('Y', strtotime($tanggal_penggajian));

        // Gaji bulan sebelumnya
        $batch = DB::table('batch_gaji_per_bulan AS batch')
                    ->where('kd_entitas', $kd_entitas)
                    ->whereMonth('tanggal_input', $beforeMonth)
                    ->orderByDesc('id')
                    ->first();
        $start_date = $currentYear.'-'.$beforeMonth.'-26';
        if ($batch) {
            $start_date = date('Y-m-d', strtotime($batch->tanggal_input. ' + 1 days'));
        }

        return $start_date;
    }

    public static function getTerutang($bulan, $tahun, $karyawan) {
        $gaji = DB::table('gaji_per_bulan AS gaji')
                    ->select(
                        'gaji.id',
                        'batch.tanggal_input',
                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_teller + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji"),
                        DB::raw("(uang_makan + tj_transport + tj_pulsa + tj_vitamin) AS tunjangan_rutin"),
                    )
                    ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                    ->where('gaji.nip', $karyawan->nip)
                    ->where('gaji.bulan', (int) $bulan)
                    ->where('gaji.tahun', (int) $tahun)
                    ->first();
        $gaji_id = 0;
        $tanggal_input = $tahun.'-'.$bulan.'-'.'25';
        $total_gaji = 0;
        $tunjangan_rutin = 0;
        if ($gaji) {
            $gaji_id = $gaji->id;
            $tanggal_input = $gaji->tanggal_input;
            $total_gaji = $gaji->total_gaji;
            $tunjangan_rutin = $gaji->tunjangan_rutin;
        }
        // PPH final adalah hasil perhitungan saat melakukan proses final
        $pph_final = 0;
        $pph_final_obj = DB::table('pph_yang_dilunasi')
                        ->select('total_pph')
                        ->where('gaji_per_bulan_id', $gaji_id)
                        ->first();
        if ($pph_final_obj) {
            $pph_final = $pph_final_obj->total_pph;
        }

        /**
         * pph_full_month adalah hasil perhitungan dilakukan 1 bulan full.
         * jadi mulai tanggal 1 hingga tgl terakhir pada bulan tersebut.
         */
        $pph_full_month = 0;

        // Get PTKP
        $ptkp = HitungPPH::getPTKP($karyawan);

        $pph_full_month = HitungPPH::getPPh58($bulan, $tahun, $karyawan, $ptkp, $tanggal_input, $total_gaji, $tunjangan_rutin, true);

        $terutang = $pph_full_month - $pph_final;

        if ($terutang > 0) {
            // Update terutang on table
            DB::table('pph_yang_dilunasi')
                ->where('gaji_per_bulan_id', $gaji_id)
                ->update([
                    'terutang' => $terutang,
                    'updated_at' => now()
                ]);
        }

        return $terutang;
    }

    public static function getNewPPH58($tanggal, $bulan, $tahun, $karyawan) {
        $gaji = DB::table('gaji_per_bulan AS gaji')
                            ->select(
                                'gaji.id',
                                'batch.tanggal_input',
                                DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_teller + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji"),
                                DB::raw("(uang_makan + tj_transport + tj_pulsa + tj_vitamin) AS tunjangan_rutin"),
                            )
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                            ->where('gaji.nip', $karyawan->nip)
                            ->where('gaji.bulan', (int) $bulan)
                            ->where('gaji.tahun', (int) $tahun)
                            ->first();
        $gaji_id = 0;
        $tanggal_input = $tahun.'-'.$bulan.'-'.'25';
        $total_gaji = 0;
        $tunjangan_rutin = 0;
        if ($gaji) {
            $gaji_id = $gaji->id;
            $tanggal_input = $gaji->tanggal_input;
            $total_gaji = $gaji->total_gaji;
            $tunjangan_rutin = $gaji->tunjangan_rutin;
        }
        // PPH final adalah hasil perhitungan saat melakukan proses final
        $pph_final = 0;
        $pph_final_obj = DB::table('pph_yang_dilunasi')
                        ->select('total_pph')
                        ->where('gaji_per_bulan_id', $gaji_id)
                        ->first();
        if ($pph_final_obj) {
            $pph_final = $pph_final_obj->total_pph;
        }

        /**
         * pph_full_month adalah hasil perhitungan dilakukan 1 bulan full.
         * jadi mulai tanggal 1 hingga tgl terakhir pada bulan tersebut.
         */
        $pph_full_month = 0;

        // Get PTKP
        $ptkp = HitungPPH::getPTKP($karyawan);

        $batch = DB::table('gaji_per_bulan AS gaji')
                    ->select(
                        'gaji.nip',
                        'batch.*',
                    )
                    ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                    ->where('gaji.nip', $karyawan->nip)
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->first();
        $full_month = false;
        if ($batch)  {
            $full_month = $tanggal > $batch->tanggal_input;
        }

        $new_pph = HitungPPH::getPPh58($bulan, $tahun, $karyawan, $ptkp, $tanggal_input, $total_gaji, $tunjangan_rutin, $full_month);

        if ($full_month) {
            $terutang = $new_pph - $pph_final;
            if ($terutang > 0) {
                // Update terutang on table
                DB::table('pph_yang_dilunasi')
                    ->where('gaji_per_bulan_id', $gaji_id)
                    ->update([
                        'terutang' => $terutang,
                        'updated_at' => now()
                    ]);
            }
        }
        else {
            DB::table('pph_yang_dilunasi')
                ->where('nip', $karyawan->nip)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->update([
                    'total_pph' => $new_pph,
                    'updated_at' => now(),
                ]);
        }


        return $new_pph;
    }

    public static function getPTKP($karyawan) {
        if ($karyawan->status_ptkp) {
            $status = $karyawan->status_ptkp;
        }
        else {
            // Get status pernikahan untuk kode ptkp
            if ($karyawan->status == 'K' || $karyawan->status == 'Kawin') {
                $anak = DB::table('mst_karyawan')
                    ->where('keluarga.nip', $karyawan->nip)
                    ->whereIn('enum', ['Suami', 'Istri'])
                    ->join('keluarga', 'keluarga.nip', 'mst_karyawan.nip')
                    ->orderByDesc('keluarga.id')
                    ->first('keluarga.jml_anak');
                if ($anak != null && $anak->jml_anak > 3) {
                    $status = 'K/3';
                } else if ($anak != null) {
                    $jml_anak = $anak->jml_anak ? $anak->jml_anak : 0;
                    $status = 'K/' . $jml_anak;
                } else {
                    $status = 'K/0';
                }
            }
            else {
                $status = 'TK/0';
            }
        }
        // Get PTKP
        $ptkp = DB::table('set_ptkp')
                ->where('kode', $status)
                ->first();
        return $ptkp;
    }

    public static function getJamsostek($karyawan, $total_gaji) {
        $kd_entitas = null;
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);
        if (!$karyawan->kd_entitas) {
            $kd_entitas = '000';
        } else {
            if (in_array($karyawan->kd_entitas, $kode_cabang_arr)) {
                $kd_entitas = $karyawan->kd_entitas;
            }
            else {
                $kd_entitas = '000';
            }
        }

        // Get Jamsostek
        $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                                ->where('kd_cabang', $kd_entitas)
                                ->where('active', 1)
                                ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                                ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                                ->first();
        $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                                ->where('kd_cabang', $kd_entitas)
                                ->where('active', 1)
                                ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                                ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                                ->first();

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
        }else{
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
        $total_gaji = floor($total_gaji);
        $jamsostek = 0;

        if($total_gaji > 0){
            $bpjs_kesehatan = 0;
            $jkk = 0;
            $jht = 0;
            $jkm = 0;
            $jp_penambah = 0;
            if(!$karyawan->tanggal_penonaktifan && $karyawan->kpj){
                $jkk = floor(($persen_jkk / 100) * $total_gaji);
                $jht = floor(($persen_jht / 100) * $total_gaji);
                $jkm = floor(($persen_jkm / 100) * $total_gaji);
                $jp_penambah = floor(($persen_jp_penambah / 100) * $total_gaji);
            }

            if($karyawan->jkn){
                if($total_gaji > $batas_atas){
                    $bpjs_kesehatan = floor(($batas_atas * ($persen_kesehatan / 100)));
                } else if($total_gaji < $batas_bawah){
                    $bpjs_kesehatan = floor(($batas_bawah * ($persen_kesehatan / 100)));
                } else{
                    $bpjs_kesehatan = floor(($total_gaji * ($persen_kesehatan / 100)));
                }
            }
            $jamsostek = $jkk + $jht + $jkm + $bpjs_kesehatan + $jp_penambah;
        }

        return $jamsostek;
    }

    public static function getTarifEfektifKategori($status_ptkp) {
        $ter_a = [
            'TK/0',
            'TK/1',
            'K/0'
        ];
        $ter_b = [
            'TK/2',
            'K/1',
            'TK/3',
            'K/2',
        ];
        $ter_c = [
            'K/3'
        ];
        if (in_array($status_ptkp, $ter_a)) {
            return 'Ter A';
        }
        else if (in_array($status_ptkp, $ter_b)) {
            return 'Ter B';
        }
        else if (in_array($status_ptkp, $ter_c)) {
            return 'Ter C';
        }
        else {
            return 'undifined';
        }
    }

    public static function getPajakInsentif(int $nominal, $tipe = 'kredit') {
        $pengali = 0;
        if ($tipe == 'kredit') {
            $pengali = floatval(config('global.pengali_insentif_kredit'));
        }
        if ($tipe == 'penagihan') {
            $pengali = floatval(config('global.pengali_insentif_penagihan'));
        }

        $result = $nominal * $pengali;

        return floor($result);
    }

    // function getPPHBulanIni($bulan, $tahun, $karyawan, $ptkp, $tanggal)
    // {
    //     $pph = 0;
    //     if (intval($bulan) > 1) {
    //         $tunjangan = array();
    //         $tunjanganJamsostek = array();
    //         $pengurang = array();
    //         $totalGaji = array();
    //         $totalGajiJamsostek = array();
    //         $penambah = array();
    //         $tunjanganBulanIni = 0;
    //         $tjJamsostekBulanIni = 0;
    //         $totalGajiBulanIni = 0;
    //         $tKeluarga = 0;
    //         $tKesejahteraan = 0;

    //         $tjBulanIni = DB::table('tunjangan_karyawan')
    //                         ->select('tunjangan_karyawan.*', 'm.kategori', 'm.status')
    //                         ->join('mst_tunjangan AS m', 'm.id', 'tunjangan_karyawan.id_tunjangan')
    //                         ->where('nip', $karyawan->nip)
    //                         ->where(function($query) {
    //                             $query->where('m.kategori', 'teratur')
    //                                 ->orWhereNull('m.kategori')
    //                                 ->where('status', 1);
    //                         })
    //                         ->get();

    //         foreach ($tjBulanIni as $key => $value) {
    //             $tunjanganBulanIni += $value->nominal;
    //             if ($value->id_tunjangan == 1) $tKeluarga += $value->nominal;
    //             if ($value->id_tunjangan == 8) $tKesejahteraan += $value->nominal;
    //             if ($value->status == 1) $tjJamsostekBulanIni += $value->nominal;
    //         }

    //         $penghasilanTidakTeraturBulanIni = DB::table('penghasilan_tidak_teratur')
    //             ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
    //             ->where('m.kategori', 'tidak teratur')
    //             ->where('nip', $karyawan->nip)
    //             ->where('bulan', intval($bulan))
    //             ->where('tahun', intval($tahun))
    //             ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
    //             ->sum('nominal');
    //         $dataGaji = DB::table('gaji_per_bulan')
    //             ->where('nip', $karyawan->nip)
    //             ->where('tahun', $tahun)
    //             ->where('bulan', '<', intval($bulan))
    //             ->get();
    //         $bonusBulanIni = DB::table('penghasilan_tidak_teratur')
    //             ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
    //             ->where('m.kategori', 'bonus')
    //             ->where('nip', $karyawan->nip)
    //             ->where('tahun', intval($tahun))
    //             ->where('bulan', intval($bulan))
    //             ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
    //             ->sum('nominal');

    //         // Bonus bulan sebelumnya
    //         $bonus = DB::table('penghasilan_tidak_teratur')
    //             ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
    //             ->where('m.kategori', 'bonus')
    //             ->where('nip', $karyawan->nip)
    //             ->where('tahun', intval($tahun))
    //             ->where('bulan', '<', intval($bulan))
    //             ->sum('nominal');

    //         foreach ($dataGaji as $key => $gaji) {
    //             $this->param['nominalJp'] = ($key < 2) ? $this->param['jpJanFeb'] : $this->param['jpMarDes'];
    //             unset($tunjangan);
    //             unset($tunjanganJamsostek);
    //             $tunjangan = array();
    //             $tunjanganJamsostek = array();
    //             $penghasilanTidakTeratur = DB::table('penghasilan_tidak_teratur')
    //                 ->where('nip', $karyawan->nip)
    //                 ->where('tahun', $tahun)
    //                 ->where('bulan', $key + 1)
    //                 ->sum('nominal');

    //             foreach ($this->param['namaTunjangan'] as $keyTunjangan => $item) {
    //                 array_push($tunjangan, $gaji->$item);
    //                 if ($keyTunjangan < 11)
    //                     array_push($tunjanganJamsostek, $gaji->$item);
    //             }

    //             $totalGj = $gaji->gj_pokok + $gaji->gj_penyesuaian;
    //             $totalGjJamsotek = $totalGj + array_sum($tunjanganJamsostek);
    //             $totalGj += $penghasilanTidakTeratur + array_sum($tunjangan) + $this->getPenambah($totalGjJamsotek, $karyawan->jkn);
    //             array_push($pengurang, $this->getPengurang($karyawan->status_karyawan, $gaji->tj_keluarga, $gaji->tj_kesejahteraan, $totalGjJamsotek, $gaji->gj_pokok));
    //             array_push($totalGaji, $totalGj);
    //             array_push($totalGajiJamsostek, $totalGjJamsotek);
    //             array_push($penambah, $this->getPenambah($totalGjJamsotek, $karyawan->jkn));
    //         }
    //         $totalGajiBulanIni = $karyawan->gj_pokok + $karyawan->gj_penyesuaian;
    //         $totalGjJamsostekBulanIni = $totalGajiBulanIni + $tjJamsostekBulanIni;
    //         $totalGajiBulanIni += $penghasilanTidakTeraturBulanIni + $tunjanganBulanIni  + $this->getPenambah($totalGjJamsostekBulanIni, $karyawan->jkn);
    //         $bonus += $bonusBulanIni;

    //         array_push($pengurang, $this->getPengurang($karyawan->status_karyawan, $tKeluarga, $tKesejahteraan, $totalGjJamsostekBulanIni, $karyawan->gj_pokok));
    //         array_push($totalGaji, $totalGajiBulanIni);
    //         array_push($totalGajiJamsostek, $totalGjJamsostekBulanIni);
    //         array_push($penambah, $this->getPenambah($totalGjJamsostekBulanIni, $karyawan->jkn));
    //     } else {
    //         $this->param['nominalJp'] = ($bulan <= 2) ? $this->param['jpJanFeb'] : $this->param['jpMarDes'];
    //         $tunjangan = array();
    //         $tunjanganJamsostek = array();
    //         $pengurang = array();
    //         $totalGaji = array();
    //         $totalGajiJamsostek = array();
    //         $penambah = array();
    //         $tunjanganBulanIni = 0;
    //         $tjJamsostekBulanIni = 0;
    //         $totalGajiBulanIni = 0;
    //         $tKeluarga = 0;
    //         $tKesejahteraan = 0;

    //         $tjBulanIni = DB::table('tunjangan_karyawan')
    //                         ->select('tunjangan_karyawan.*', 'm.kategori', 'm.status')
    //                         ->join('mst_tunjangan AS m', 'm.id', 'tunjangan_karyawan.id_tunjangan')
    //                         ->where('nip', $karyawan->nip)
    //                         ->where(function($query) {
    //                             $query->where('m.kategori', 'teratur')
    //                                 ->orWhereNull('m.kategori')
    //                                 ->where('status', 1);
    //                         })
    //                         ->get();

    //         foreach ($tjBulanIni as $key => $value) {
    //             $tunjanganBulanIni += $value->nominal;
    //             if ($value->id_tunjangan == 1) $tKeluarga += $value->nominal;
    //             if ($value->id_tunjangan == 8) $tKesejahteraan += $value->nominal;
    //             if ($value->status == 1) $tjJamsostekBulanIni += $value->nominal;
    //         }

    //         $penghasilanTidakTeraturBulanIni = DB::table('penghasilan_tidak_teratur')
    //             ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
    //             ->where('m.kategori', 'tidak teratur')
    //             ->where('nip', $karyawan->nip)
    //             ->where('bulan', $bulan)
    //             ->where('tahun', $tahun)
    //             ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
    //             ->sum('penghasilan_tidak_teratur.nominal');
    //         $bonus = DB::table('penghasilan_tidak_teratur')
    //             ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
    //             ->where('m.kategori', 'bonus')
    //             ->where('nip', $karyawan->nip)
    //             ->where('tahun', intval($tahun))
    //             ->where('bulan', intval($bulan))
    //             ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
    //             ->sum('penghasilan_tidak_teratur.nominal');
    //         $totalGajiBulanIni = $karyawan->gj_pokok + $karyawan->gj_penyesuaian;
    //         $totalGjJamsostekBulanIni = $totalGajiBulanIni + $tjJamsostekBulanIni;
    //         $totalGajiBulanIni += $penghasilanTidakTeraturBulanIni + $tunjanganBulanIni + $this->getPenambah($totalGjJamsostekBulanIni, $karyawan->jkn);

    //         array_push($pengurang, $this->getPengurang($karyawan->status_karyawan, $tKeluarga, $tKesejahteraan, $totalGjJamsostekBulanIni, $karyawan->gj_pokok));
    //         array_push($totalGaji, $totalGajiBulanIni);
    //         array_push($totalGajiJamsostek, $totalGjJamsostekBulanIni);
    //         array_push($penambah, $this->getPenambah($totalGjJamsostekBulanIni, $karyawan->jkn));
    //     }

    //     $lima_persen = ceil(0.05 * array_sum($totalGaji));
    //     $keterangan = 500000 * intval($bulan);
    //     $biaya_jabatan = 0;
    //     if ($lima_persen > $keterangan) {
    //         $biaya_jabatan = $keterangan;
    //     } else {
    //         $biaya_jabatan = $lima_persen;
    //     }
    //     $rumus_14 = 0;
    //     if (0.05 * (array_sum($totalGaji)) > $keterangan) {
    //         $rumus_14 = ceil($keterangan);
    //     } else {
    //         $rumus_14 = ceil(0.05 * (array_sum($totalGaji)));
    //     }
    //     $no_14 = ((array_sum($totalGaji) - $bonus - array_sum($pengurang) - $biaya_jabatan) / intval($bulan) * 12 + $bonus + ($biaya_jabatan - $rumus_14));

    //     $persen5 = 0;
    //     if (($no_14 - $ptkp?->ptkp_tahun) > 0) {
    //         if (($no_14 - $ptkp?->ptkp_tahun) <= 60000000) {
    //             $persen5 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.05 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.06;
    //         } else {
    //             $persen5 = ($karyawan->npwp != null) ? 60000000 * 0.05 : 60000000 * 0.06;
    //         }
    //     } else {
    //         $persen5 = 0;
    //     }
    //     $persen15 = 0;
    //     if (($no_14 - $ptkp?->ptkp_tahun) > 60000000) {
    //         if (($no_14 - $ptkp?->ptkp_tahun) <= 250000000) {
    //             $persen15 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.15 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.18;
    //         } else {
    //             $persen15 = 190000000 * 0.15;
    //         }
    //     } else {
    //         $persen15 = 0;
    //     }
    //     $persen25 = 0;
    //     if (($no_14 - $ptkp?->ptkp_tahun) > 250000000) {
    //         if (($no_14 - $ptkp?->ptkp_tahun) <= 500000000) {
    //             $persen25 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.25 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.3;
    //         } else {
    //             $persen25 = 250000000 * 0.25;
    //         }
    //     } else {
    //         $persen25 = 0;
    //     }
    //     $persen30 = 0;
    //     if (($no_14 - $ptkp?->ptkp_tahun) > 500000000) {
    //         if (($no_14 - $ptkp?->ptkp_tahun) <= 5000000000) {
    //             $persen30 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.3 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.36;
    //         } else {
    //             $persen30 = 4500000000 * 0.30;
    //         }
    //     } else {
    //         $persen30 = 0;
    //     }
    //     $persen35 = 0;
    //     if (($no_14 - $ptkp?->ptkp_tahun) > 5000000000) {
    //         $persen35 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.35 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.42;
    //     } else {
    //         $persen35 = 0;
    //     }

    //     $no17 = (($persen5 + $persen15 + $persen25 + $persen30 + $persen35) / 1000) * 1000;

    //     $pph = floor(($no17 / 12) * intval($bulan));
    //     if (intval($bulan) > 1) {
    //         $pphTerbayar = (int) DB::table('pph_yang_dilunasi')
    //             ->where('nip', $karyawan->nip)
    //             ->where('tahun', $tahun)
    //             ->sum('total_pph');
    //         $pph -= $pphTerbayar;
    //     }
    //     return round($pph);
    // }
}
