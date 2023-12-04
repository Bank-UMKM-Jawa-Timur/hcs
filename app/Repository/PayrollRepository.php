<?php

namespace App\Repository;

use App\Models\KaryawanModel;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class PayrollRepository
{
    public function get($kantor, $month, $year, $search, $page=1, $limit=10) {
        /**
         * PPH 21
         * Gaji - done
         * Tunjangan Tetap - done
         * Tunjangan Tidak Tetap - done
         * BPJS TK - done
         * BPJS Kesehatan - done
         * Tambahan Penghasilan(bonus) - done
         * Potongan (JP1%, DPP 5%, Kredit Koperasi, Iuran Koperasi, Kredit Pegawai, Iuran IK) - done
         */

        /**
          * Filter
          * Kantor(Pusat/Cabang)
          * Bulan
          * Tahun
          */

        /**
         * Table
         * gaji = gaji_perbulan
         * tunjangan tetap = tunjangan_karyawan
         * tunjangan tidak tetap = penghasilan_tidak_teratur
         * bpjs tk = kpj (jamsostek)
         * bpjs kesehatan = jkn 
         */

        $kode_cabang_arr = [];
        
        if($kantor == 'pusat'){
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
        else {
            $cabangRepo = new CabangRepository;
            $kode_cabang_arr = $cabangRepo->listCabang(true);

            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                ->where('mst_profil_kantor.kd_cabang', $kantor)
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                ->first();
            $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                ->where('kd_cabang', $kantor)
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

        $data = KaryawanModel::with([
                                'gaji' => function($query) use ($month, $year) {
                                    $query->select(
                                        'nip',
                                        'bulan',
                                        'tahun',
                                        'gj_pokok',
                                        'gj_penyesuaian',
                                        'tj_keluarga',
                                        'tj_telepon',
                                        'tj_jabatan',
                                        'tj_teller',
                                        'tj_perumahan',
                                        'tj_kemahalan',
                                        'tj_pelaksana',
                                        'tj_kesejahteraan',
                                        'tj_multilevel',
                                        'tj_ti',
                                        'tj_transport',
                                        'tj_pulsa',
                                        'tj_vitamin',
                                        'uang_makan',
                                        'dpp',
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan) AS total_gaji")
                                    )
                                    ->where('bulan', $month)
                                    ->where('tahun', $year);
                                },
                                'tunjangan' => function($query) use ($month, $year) {
                                    $query->whereMonth('tunjangan_karyawan.created_at', $month)
                                        ->whereYear('tunjangan_karyawan.created_at', $year);
                                },
                                'tunjanganTidakTetap' => function($query) use ($month, $year) {
                                    $query->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('mst_tunjangan.kategori', 'tidak teratur');
                                },
                                'bonus' => function($query) use ($month, $year) {
                                    $query->where('penghasilan_tidak_teratur.bulan', $month)
                                        ->where('penghasilan_tidak_teratur.tahun', $year)
                                        ->where('mst_tunjangan.kategori', 'bonus');
                                },
                                'potonganGaji' => function($query) use ($month, $year) {
                                        $query->select(
                                            'potongan_gaji.nip',
                                            DB::raw('SUM(potongan_gaji.kredit_koperasi) AS kredit_koperasi'),
                                            DB::raw('SUM(potongan_gaji.iuran_koperasi) AS iuran_koperasi'),
                                            DB::raw('SUM(potongan_gaji.kredit_pegawai) AS kredit_pegawai'),
                                            DB::raw('SUM(potongan_gaji.iuran_ik) AS iuran_ik'),
                                            DB::raw('(SUM(potongan_gaji.kredit_koperasi) + SUM(potongan_gaji.iuran_koperasi) + SUM(potongan_gaji.kredit_pegawai) + SUM(potongan_gaji.iuran_ik)) AS total_potongan'),
                                        )
                                        ->where('potongan_gaji.bulan', $month)
                                        ->where('potongan_gaji.tahun', $year)
                                        ->groupBy('potongan_gaji.bulan')
                                        ->groupBy('potongan_gaji.tahun')
                                        ->sum('potongan_gaji.kredit_koperasi');
                                }
                            ])
                            ->select(
                                'nip',
                                'nama_karyawan',
                                'no_rekening',
                                'tanggal_penonaktifan',
                                'kpj',
                                'jkn',
                                'status_karyawan',
                            )
                            ->leftJoin('mst_cabang AS c', 'c.kd_cabang', 'kd_entitas')
                            ->where(function($query) use ($month, $year, $kantor, $kode_cabang_arr, $search) {
                                $query->whereRelation('gaji', 'bulan', $month)
                                ->whereRelation('gaji', 'tahun', $year)
                                ->where(function($q) use ($kantor, $kode_cabang_arr, $search) {
                                    if ($kantor == 'pusat') {
                                        $q->whereNotIn('mst_karyawan.kd_entitas', $kode_cabang_arr);
                                    }
                                    else {
                                        $q->orWhere('mst_karyawan.kd_entitas', $kantor);
                                    }
                                    $q->where('mst_karyawan.nama_karyawan', 'like', "%$search%");
                                });
                            })
                            ->paginate($limit);

        foreach ($data as $key => $karyawan) {
            $nominal_jp = 0;
            $jamsostek = 0;
            $bpjs_tk = 0;
            $bpjs_kesehatan = 0;
            $potongan = new \stdClass();
            $total_gaji = 0;
            $total_potongan = 0;
            $penghasilan_rutin = 0;
            $penghasilan_tidak_rutin = 0;
            $penghasilan_tidak_teratur = 0;
            $bonus = 0;
            $tunjangan_teratur_import = 0; // Uang makan, vitamin, transport & pulsa

            if ($karyawan->gaji) {
                // Get BPJS TK * Kesehatan
                $obj_gaji = $karyawan->gaji;
                $gaji = $obj_gaji->gaji;
                $total_gaji = $obj_gaji->total_gaji;

                if($total_gaji > 0){
                    $jkk = 0;
                    $jht = 0;
                    $jkm = 0;
                    $jp_penambah = 0;
                    if(!$karyawan->tanggal_penonaktifan && $karyawan->kpj){
                        $jkk = round(($persen_jkk / 100) * $total_gaji);
                        $jht = round(($persen_jht / 100) * $total_gaji);
                        $jkm = round(($persen_jkm / 100) * $total_gaji);
                        $jp_penambah = round(($persen_jp_penambah / 100) * $total_gaji);
                    }

                    if($karyawan->jkn){
                        if($total_gaji > $batas_atas){
                            $bpjs_kesehatan = round($batas_atas * ($persen_kesehatan / 100));
                        } else if($total_gaji < $batas_bawah){
                            $bpjs_kesehatan = round($batas_bawah * ($persen_kesehatan / 100));
                        } else{
                            $bpjs_kesehatan = round($total_gaji * ($persen_kesehatan / 100));
                        }
                    }
                    $jamsostek = $jkk + $jht + $jkm + $bpjs_kesehatan + $jp_penambah;
                }

                // Get Potongan(JP1%, DPP 5%)
                $nominal_jp = ($obj_gaji->bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                if($karyawan->status_karyawan == 'IKJP') {
                    $jp_1_persen = round(($persen_jp_pengurang / 100) * $gaji, 2);
                } else{
                    $gj_pokok = $obj_gaji->gj_pokok;
                    $tj_keluarga = $obj_gaji->tj_keluarga;
                    $tj_kesejahteraan = $obj_gaji->tj_kesejahteraan;

                    // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5% 
                    $dpp = (($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100);
                    if($gaji >= $nominal_jp){
                        $jp_1_persen = round($nominal_jp * ($persen_jp_pengurang / 100), 2);
                    } else {
                        $jp_1_persen = round($gaji * ($persen_jp_pengurang / 100), 2);
                    }
                }
                $potongan->dpp = $dpp;
                $potongan->jp_1_persen = $jp_1_persen;
                
                // Get BPJS TK
                if ($obj_gaji->bulan > 2) {
                    if ($total_gaji > $jp_mar_des) {
                        $bpjs_tk = $jp_mar_des * 1 / 100;
                    }
                    else {
                        $bpjs_tk = $total_gaji * 1 / 100;
                    }
                }
                else {
                    if ($total_gaji >= $jp_jan_feb) {
                        $bpjs_tk = $jp_jan_feb * 1 / 100;
                    }
                    else {
                        $bpjs_tk = $$total_gaji * 1 / 100;
                    }
                }

                // Penghasilan rutin
                $penghasilan_rutin = $gaji + $jamsostek;
            }

            $karyawan->jamsostek = $jamsostek;
            $karyawan->bpjs_tk = $bpjs_tk;
            $karyawan->bpjs_kesehatan = $bpjs_kesehatan;
            $karyawan->potongan = $potongan;

            // Get total penghasilan tidak teratur
            if ($karyawan->tunjanganTidakTetap) {
                $tunjangan_tidak_tetap = $karyawan->tunjanganTidakTetap;
                foreach ($tunjangan_tidak_tetap as $key => $value) {
                    $penghasilan_tidak_teratur += $value->pivot->nominal;
                }
            }

            // Get total bonus
            if ($karyawan->bonus) {
                $bonus_item = $karyawan->bonus;
                foreach ($bonus_item as $key => $value) {
                    $bonus += $value->pivot->nominal;
                }
            }

            // Penghasilan tidak rutin
            $penghasilan_tidak_rutin = $penghasilan_tidak_teratur + $bonus;

            // Get total potongan
            if ($karyawan->potonganGaji) {
                $total_potongan += $karyawan->potonganGaji->total_potongan;
            }

            if ($karyawan->potongan) {
                $total_potongan += $karyawan->potongan->dpp;
            }
            
            $total_potongan += $bpjs_tk;
            $karyawan->total_potongan = $total_potongan;

            // Get total yg diterima
            $total_yg_diterima = $total_gaji - $total_potongan;
            $karyawan->total_yg_diterima = $total_yg_diterima;

            $karyawan->penghasilan_rutin = $penghasilan_rutin;
            $karyawan->penghasilan_tidak_rutin = $penghasilan_tidak_rutin;
            
            // Get Penghasilan bruto
            $month_on_year = 12;
            $month_on_year_paid = 0;
            $penghasilanBruto = new \stdClass();
            $karyawan_bruto = KaryawanModel::with([
                                                'allGajiByKaryawan' => function($query) use ($karyawan, $year) {
                                                    $query->select(
                                                        'nip',
                                                        'bulan',
                                                        'gj_pokok',
                                                        'tj_keluarga',
                                                        'tj_kesejahteraan',
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_telepon + tj_jabatan + tj_teller + tj_perumahan  + tj_kemahalan + tj_pelaksana + tj_kesejahteraan + tj_multilevel + tj_ti + tj_transport + tj_pulsa + tj_vitamin + uang_makan) AS gaji"),
                                                        DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan) AS total_gaji"),
                                                    )
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('tahun', $year)
                                                    ->groupBy('bulan');
                                                },
                                                'sumBonusKaryawan' => function($query) use ($karyawan) {
                                                    $query->select(
                                                        'penghasilan_tidak_teratur.nip',
                                                        'mst_tunjangan.kategori',
                                                        DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                    )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('mst_tunjangan.kategori', 'bonus')
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                                'sumTunjanganTidakTetapKaryawan' => function($query) use ($karyawan) {
                                                    $query->select(
                                                            'penghasilan_tidak_teratur.nip',
                                                            'mst_tunjangan.kategori',
                                                            DB::raw("SUM(penghasilan_tidak_teratur.nominal) AS total"),
                                                        )
                                                        ->where('penghasilan_tidak_teratur.nip', $karyawan->nip)
                                                        ->where('mst_tunjangan.kategori', 'tidak teratur')
                                                        ->groupBy('penghasilan_tidak_teratur.nip');
                                                },
                                            ])
                                            ->select(
                                                'nip',
                                                'nama_karyawan',
                                                'no_rekening',
                                                'tanggal_penonaktifan',
                                                'kpj',
                                                'jkn',
                                                'status_karyawan',
                                            )
                                            ->leftJoin('mst_cabang AS c', 'c.kd_cabang', 'kd_entitas')
                                            ->where(function($query) use ($karyawan) {
                                                $query->whereRelation('allGajiByKaryawan', 'nip', $karyawan->nip);
                                            })
                                            ->first();

            if ($karyawan_bruto) {
                $gaji_bruto = 0;
                // Get jamsostek
                if ($karyawan_bruto->allGajiByKaryawan) {
                    $month_on_year_paid = count($karyawan_bruto->allGajiByKaryawan);
                    $allGajiByKaryawan = $karyawan_bruto->allGajiByKaryawan;
                    $total_gaji_bruto = 0;
                    $total_jamsostek = 0;
                    $total_pengurang_bruto = 0;
                    foreach ($allGajiByKaryawan as $key => $value) {
                        $gaji_bruto += $value->gaji ? intval($value->gaji) : 0;
                        $total_gaji = $value->total_gaji ? intval($value->total_gaji) : 0;
                        $total_gaji_bruto += $total_gaji;
                        $pengurang_bruto = 0;

                        // Get jamsostek
                        if($total_gaji > 0){
                            $jkk = 0;
                            $jht = 0;
                            $jkm = 0;
                            $jp_penambah = 0;
                            $bpjs_kesehatan = 0;
                            if(!$karyawan_bruto->tanggal_penonaktifan && $karyawan_bruto->kpj){
                                $jkk = round(($persen_jkk / 100) * $total_gaji);
                                $jht = round(($persen_jht / 100) * $total_gaji);
                                $jkm = round(($persen_jkm / 100) * $total_gaji);
                                $jp_penambah = round(($persen_jp_penambah / 100) * $total_gaji);
                            }

                            if($karyawan_bruto->jkn){
                                if($total_gaji > $batas_atas){
                                    $bpjs_kesehatan = round($batas_atas * ($persen_kesehatan / 100));
                                } else if($total_gaji < $batas_bawah){
                                    $bpjs_kesehatan = round($batas_bawah * ($persen_kesehatan / 100));
                                } else{
                                    $bpjs_kesehatan = round($total_gaji * ($persen_kesehatan / 100));
                                }
                            }
                            $jamsostek = $jkk + $jht + $jkm + $bpjs_kesehatan + $jp_penambah;

                            $total_jamsostek += $jamsostek;

                            // Get Potongan(JP1%, DPP 5%)
                            $nominal_jp = ($value->bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                            $dppBruto = 0;
                            $dppBrutoExtra = 0;
                            if($karyawan->status_karyawan == 'IKJP') {
                                $dppBrutoExtra = round(($persen_jp_pengurang / 100) * $total_gaji, 2);
                            } else{
                                $gj_pokok = $value->gj_pokok;
                                $tj_keluarga = $value->tj_keluarga;
                                $tj_kesejahteraan = $value->tj_kesejahteraan;

                                // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5% 
                                $dppBruto = (($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100);
                                if($total_gaji >= $nominal_jp){
                                    $dppBrutoExtra = round($nominal_jp * ($persen_jp_pengurang / 100), 2);
                                } else {
                                    $dppBrutoExtra = round($total_gaji * ($persen_jp_pengurang / 100), 2);
                                }
                            }

                            $pengurang_bruto = $dppBruto + $dppBrutoExtra;
                            $total_pengurang_bruto += $pengurang_bruto;
                        }
                        $value->pengurangan_bruto = $pengurang_bruto;
                    }
                    $penghasilanBruto->total_pengurangan_bruto = $total_pengurang_bruto;
                    $penghasilanBruto->gaji_pensiun = intval($total_gaji_bruto);
                    $penghasilanBruto->total_jamsostek = intval($total_jamsostek);
                }

                // Get penghasilan tidak teratur
                $total_penghasilan_tidak_teratur_bruto = 0;
                if ($karyawan_bruto->sumTunjanganTidakTetapKaryawan) {
                    $sumTunjanganTidakTetapKaryawan = $karyawan_bruto->sumTunjanganTidakTetapKaryawan;
                    $total_penghasilan_tidak_teratur_bruto = isset($sumTunjanganTidakTetapKaryawan[0]) ? intval($sumTunjanganTidakTetapKaryawan[0]->total) : 0;
                    $penghasilanBruto->total_penghasilan_tidak_teratur = $total_penghasilan_tidak_teratur_bruto;
                }

                // Get total bonus
                $total_bonus_bruto = 0;
                if ($karyawan_bruto->sumBonusKaryawan) {
                    $sumBonusKaryawan = $karyawan_bruto->sumBonusKaryawan;
                    $total_bonus_bruto = isset($sumBonusKaryawan[0]) ? intval($sumBonusKaryawan[0]->total) : 0;
                    $penghasilanBruto->total_bonus = $total_bonus_bruto;
                }

                $penghasilan_rutin_bruto = 0;
                $penghasilan_tidak_rutin_bruto = 0;
                $total_penghasilan = 0; // ( Teratur + Tidak Teratur )

                $penghasilan_rutin_bruto = $gaji_bruto;
                $penghasilan_tidak_rutin_bruto = $total_penghasilan_tidak_teratur_bruto + $total_bonus_bruto;
                $total_penghasilan = $penghasilan_rutin_bruto + $penghasilan_tidak_rutin_bruto;
                $penghasilanBruto->penghasilan_rutin = $penghasilan_rutin_bruto;
                $penghasilanBruto->penghasilan_tidak_rutin = $penghasilan_tidak_rutin_bruto;
                $penghasilanBruto->total_penghasilan = $total_penghasilan;
            }

            // Get Tunjangan lainnya (T.Makan, T.Pulsa, T.Transport, T.Vitamin, T.Tidak teratur, Bonus)
            if ($karyawan->tunjangan) {
                $tunjangan = $karyawan->tunjangan;
                foreach ($tunjangan as $key => $value) {
                    $tunjangan_teratur_import += $value->pivot->nominal;
                }
            }
            $tunjangan_lainnya = $tunjangan_teratur_import + $penghasilan_tidak_rutin;
            $penghasilanBruto->tunjangan_lainnya = $tunjangan_lainnya;

            // Get total penghasilan bruto
            $total_penghasilan_bruto = 0;
            if (property_exists($penghasilanBruto, 'gaji_pensiun')) {
                $total_penghasilan_bruto += $penghasilanBruto->gaji_pensiun;
            }
            if (property_exists($penghasilanBruto, 'total_jamsostek')) {
                $total_penghasilan_bruto += $penghasilanBruto->total_jamsostek;
            }
            if (property_exists($penghasilanBruto, 'total_bonus')) {
                $total_penghasilan_bruto += $penghasilanBruto->total_bonus;
            }
            if (property_exists($penghasilanBruto, 'tunjangan_lainnya')) {
                $total_penghasilan_bruto += $penghasilanBruto->tunjangan_lainnya;
            }
            $penghasilanBruto->total_keseluruhan = $total_penghasilan_bruto;

            /**
             * Pengurang Penghasilan
             * IF(5%*K46>500000*SUM(L18:L29);500000*SUM(L18:L29);5%*K46)
             * K46 = total penghasilan
             * SUM(L18:19) = jumlah bulan telah digaji dalam setahun ($month_on_year_paid)
             */

            $biaya_jabatan = 0;
            if (property_exists($penghasilanBruto, 'total_penghasilan')) {
                $pembanding = 500000 * $month_on_year_paid;
                $biaya_jabatan = (0.05 * $penghasilanBruto->total_penghasilan) > $pembanding ? $pembanding : (0.05 * $penghasilanBruto->total_penghasilan);
            }

            $karyawan->karyawan_bruto = $karyawan_bruto;
            $karyawan->penghasilan_bruto = $penghasilanBruto;
        }
        return $data;
    }
}