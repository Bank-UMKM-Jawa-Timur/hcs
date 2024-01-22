<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class GajiPerBulanRepository
{
    public function getPenghasilanList($status, $limit=10, $page = 1) {
        $is_cabang = auth()->user()->hasRole('cabang');
        $is_pusat = auth()->user()->hasRole('kepegawaian');
        $kd_cabang = DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();

        $data = DB::table('batch_gaji_per_bulan AS batch')
                ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
                ->join('pph_yang_dilunasi AS pph', 'pph.gaji_per_bulan_id', 'gaji.id')
                ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                ->join('mst_cabang AS cab', 'cab.kd_cabang', 'batch.kd_entitas')
                ->select(
                    'batch.id',
                    'batch.kd_entitas',
                    'cab.nama_cabang AS kantor',
                    'batch.tanggal_input',
                    'batch.tanggal_final',
                    'batch.tanggal_cetak',
                    'batch.tanggal_upload',
                    'batch.file',
                    'batch.status',
                    'gaji.bulan',
                    'gaji.tahun',
                    DB::raw('CAST(SUM(pph.total_pph) AS UNSIGNED) AS total_pph'),
                    DB::raw('CAST(SUM(gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti) AS UNSIGNED) AS bruto'),
                    DB::raw('CAST(SUM(gaji.kredit_koperasi + gaji.iuran_koperasi + gaji.kredit_pegawai + gaji.iuran_ik) AS UNSIGNED) AS total_potongan'),
                )
                ->when($is_cabang, function($query) {
                    $kd_cabang = auth()->user()->kd_cabang;
                    $query->where('m.kd_entitas', $kd_cabang);
                })
                ->when($is_pusat, function($query) use ($kd_cabang) {
                    $query->where(function($q2) use ($kd_cabang) {
                        $q2->whereNotIn('m.kd_entitas', $kd_cabang)
                            ->orWhere('m.kd_entitas', 0)
                            ->orWhereNull('m.kd_entitas');
                    });
                })
                ->where('batch.status', $status)
                ->orderBy('gaji.created_at', 'desc')
                ->groupBy('batch.kd_entitas')
                ->groupBy('gaji.bulan')
                ->groupBy('gaji.tahun')
                ->paginate($limit);
                // ->paginate($limit, ['*'], 'page', $page);

        foreach ($data as $key => $item) {
            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                ->where('mst_profil_kantor.kd_cabang', $item->kd_entitas)
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
            // Get Bruto & Netto
            $netto = 0;
            $grand_total_potongan = $item->total_potongan;

            // Get Potongan(JP1%, DPP 5%)
            $gaji_obj = DB::table('gaji_per_bulan AS gaji')
                        ->select(
                            'm.nama_karyawan',
                            'm.npwp',
                            'm.no_rekening',
                            'm.tanggal_penonaktifan',
                            'm.kpj',
                            'm.jkn',
                            'm.status_karyawan',
                            'gaji.bulan',
                            'gaji.tahun',
                            'gaji.gj_pokok',
                            'gaji.tj_keluarga',
                            'gaji.tj_kesejahteraan',
                            DB::raw('CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional + gaji.tj_transport + gaji.tj_pulsa + gaji.tj_vitamin + gaji.uang_makan) AS UNSIGNED) AS gaji'),
                            DB::raw("CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_jabatan + gaji.tj_perumahan + gaji.tj_telepon + gaji.tj_pelaksana + gaji.tj_kemahalan + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) AS UNSIGNED) AS total_gaji"),
                        )
                        ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                        ->where('gaji.batch_id', $item->id)
                        ->get();
            $total_dpp = 0;
            $total_bpjs_tk = 0;
            foreach ($gaji_obj as $value) {
                $gaji = $value->gaji;
                $total_gaji = $value->total_gaji;
                $nominal_jp = ($value->bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                if($value->status_karyawan == 'IKJP') {
                    $dpp = 0;
                    $jp_1_persen = round(($persen_jp_pengurang / 100) * $gaji, 2);
                } else{
                    $gj_pokok = $value->gj_pokok;
                    $tj_keluarga = $value->tj_keluarga;
                    $tj_kesejahteraan = $value->tj_kesejahteraan;

                    // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5%
                    $dpp = (($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100);
                    if($gaji >= $nominal_jp){
                        $jp_1_persen = round($nominal_jp * ($persen_jp_pengurang / 100), 2);
                    } else {
                        $jp_1_persen = round($gaji * ($persen_jp_pengurang / 100), 2);
                    }
                }
                $dpp = round($dpp);
                $total_dpp += $dpp;
                $jp_1_persen = $jp_1_persen;

                // Get BPJS TK
                if ($value->bulan > 2) {
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
                        $bpjs_tk = $total_gaji * 1 / 100;
                    }
                }
                $bpjs_tk = $bpjs_tk;
                $total_bpjs_tk += $bpjs_tk;
            }
            $grand_total_potongan = ($item->total_potongan + $total_bpjs_tk + $total_dpp);
            $item->grand_total_potongan= $grand_total_potongan;

            $netto = $item->bruto - $grand_total_potongan;
            $item->netto = $netto;

            // Cek apakah ada perubahan data penghasilan
            $total_penyesuaian = 0;
            if ($item->status == 'proses') {
                $data_gaji = DB::table('gaji_per_bulan AS gaji')
                                ->select(
                                    'gaji.*',
                                    'm.nama_karyawan',
                                    DB::raw('CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti) AS UNSIGNED) AS total_penghasilan'),
                                    DB::raw('CAST((gaji.kredit_koperasi + gaji.iuran_koperasi + gaji.kredit_pegawai + gaji.iuran_ik) AS UNSIGNED) AS total_potongan')
                                )
                                ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                                ->where('gaji.batch_id', $item->id)
                                ->get();

                foreach ($data_gaji as $gaji) {
                    $karyawan = DB::table('mst_karyawan')
                                ->where('nip', $gaji->nip)
                                ->first();
                    if ($gaji->gj_pokok != $karyawan->gj_pokok) {
                        $total_penyesuaian++;
                    }
                    if ($gaji->gj_penyesuaian != $karyawan->gj_penyesuaian) {
                        $total_penyesuaian++;
                    }

                    $tunjangan = DB::table('tunjangan_karyawan')
                                    ->where('nip', $gaji->nip)
                                    ->get();
                    foreach ($tunjangan as $tunj) {
                        // Keluarga
                        if ($tunj->id_tunjangan == 1) {
                            if ($gaji->tj_keluarga != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Telepon
                        if ($tunj->id_tunjangan == 2) {
                            if ($gaji->tj_telepon != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Jabatan
                        if ($tunj->id_tunjangan == 3) {
                            if ($gaji->tj_jabatan != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Teller
                        if ($tunj->id_tunjangan == 4) {
                            if ($gaji->tj_teller != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Perumahan
                        if ($tunj->id_tunjangan == 5) {
                            if ($gaji->tj_perumahan != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Kemahalan
                        if ($tunj->id_tunjangan == 6) {
                            if ($gaji->tj_kemahalan != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Pelaksana
                        if ($tunj->id_tunjangan == 7) {
                            if ($gaji->tj_pelaksana != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Kesejahteraan
                        if ($tunj->id_tunjangan == 8) {
                            if ($gaji->tj_kesejahteraan != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Multilevel
                        if ($tunj->id_tunjangan == 9) {
                            if ($gaji->tj_multilevel != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // TI
                        if ($tunj->id_tunjangan == 10) {
                            if ($gaji->tj_ti != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                    }

                    $transaksi_tunjangan = DB::table('transaksi_tunjangan')
                                            ->where('nip', $gaji->nip)
                                            ->where('bulan', $gaji->bulan)
                                            ->where('tahun', $gaji->tahun)
                                            ->get();
                    foreach ($transaksi_tunjangan as $tunj) {
                        // Transport
                        if ($tunj->id_tunjangan == 11) {
                            if ($gaji->tj_transport != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Pulsa
                        if ($tunj->id_tunjangan == 12) {
                            if ($gaji->tj_pulsa != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Vitamin
                        if ($tunj->id_tunjangan == 13) {
                            if ($gaji->tj_vitamin != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                        // Uang Makan
                        if ($tunj->id_tunjangan == 14) {
                            if ($gaji->uang_makan != $tunj->nominal) {
                                $total_penyesuaian++;
                            }
                        }
                    }

                    // Get Potongan
                    $potongan = DB::table('potongan_gaji')
                                ->where('nip', $gaji->nip)
                                ->first();

                    if ($potongan) {
                        if ($potongan->kredit_koperasi != $gaji->kredit_koperasi) {
                            $total_penyesuaian++;
                        }
                        if ($potongan->iuran_koperasi != $gaji->iuran_koperasi) {
                            $total_penyesuaian++;
                        }
                        if ($potongan->kredit_pegawai != $gaji->kredit_pegawai) {
                            $total_penyesuaian++;
                        }
                        if ($potongan->iuran_ik != $gaji->iuran_ik) {
                            $total_penyesuaian++;
                        }
                    }
                }
            }
            $item->total_penyesuaian = $total_penyesuaian;
        }

        return $data;
    }
}
