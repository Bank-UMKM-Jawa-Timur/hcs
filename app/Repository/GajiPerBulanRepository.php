<?php

namespace App\Repository;

use App\Helpers\HitungPPH;
use Illuminate\Support\Facades\DB;

class GajiPerBulanRepository
{
    public function getPenghasilanList($tahun, $bulan, $cabang, $status, $limit=10, $page = 1, $search) {
        $is_cabang = auth()->user()->hasRole('cabang');
        $is_pusat = auth()->user()->hasRole('kepegawaian');

        $data = DB::table('batch_gaji_per_bulan AS batch')
                ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
                ->join('pph_yang_dilunasi AS pph', 'pph.gaji_per_bulan_id', 'gaji.id')
                ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                ->leftJoin('mst_divisi AS md', 'md.kd_divisi', 'm.kd_entitas')
                ->join('mst_cabang AS cab', 'cab.kd_cabang', 'batch.kd_entitas')
                ->select(
                    'batch.id',
                    'batch.kd_entitas',
                    'batch.is_pegawai',
                    'cab.nama_cabang AS kantor',
                    'batch.tanggal_input',
                    'batch.tanggal_final',
                    'batch.tanggal_cetak',
                    'batch.tanggal_upload',
                    'batch.file',
                    'batch.status',
                    'gaji.bulan',
                    'gaji.tahun',
                    'gaji.dpp',
                    'gaji.jp',
                    'gaji.bpjs_tk',
                    'gaji.penambah_bruto_jamsostek',
                    DB::raw('CAST(SUM(pph.total_pph) AS SIGNED) AS total_pph'),
                    DB::raw('CAST(SUM(pph.insentif_kredit + pph.insentif_penagihan) AS SIGNED) AS total_pajak_insentif'),
                    DB::raw('CAST(SUM(pph.total_pph) - SUM(pph.insentif_kredit + pph.insentif_penagihan) AS SIGNED) AS hasil_pph'),
                    DB::raw('CAST(SUM(gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) AS SIGNED) AS bruto'),
                    DB::raw('CAST(SUM(gaji.kredit_koperasi + gaji.iuran_koperasi + gaji.kredit_pegawai + gaji.iuran_ik + gaji.dpp + gaji.bpjs_tk) AS SIGNED) AS total_potongan'),
                    DB::raw('CAST(SUM(gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) - SUM(gaji.kredit_koperasi + gaji.iuran_koperasi + gaji.kredit_pegawai + gaji.iuran_ik + gaji.dpp + gaji.bpjs_tk) AS SIGNED) AS netto'),
                    'm.kd_entitas AS entitas_karyawan',
                    'md.nama_divisi',
                )
                ->where(function ($query) use ($search) {
                    $query->where('gaji.tahun', 'like', "%$search%")
                        ->orWhere('cab.nama_cabang', 'like', "%$search%");
                })
                ->where(function ($query) use ($tahun) {
                    if ($tahun) {
                        $query->whereYear('batch.tanggal_input', $tahun);
                    }
                })
                ->where(function ($query) use ($bulan) {
                    if ($bulan) {
                        $query->whereMonth('batch.tanggal_input', $bulan);
                    }
                })
                ->where(function ($query) use ($cabang) {
                    if ($cabang != null) {
                        $query->where('batch.kd_entitas', $cabang);
                    }
                })
                ->when($is_cabang, function($query) {
                    $kd_cabang = auth()->user()->kd_cabang;
                    $query->where('batch.kd_entitas', $kd_cabang);
                })
                ->where('batch.status', $status)
                ->whereNull('batch.deleted_at')
                ->orderBy('batch.kd_entitas')
                ->groupBy('batch.kd_entitas')
                ->groupBy('batch.id')
                ->groupBy('gaji.bulan')
                ->groupBy('gaji.tahun')
                ->paginate($limit,['*'], 'page', $page);

        foreach ($data as $item) {
            $kd_entitas = $item->kd_entitas;
            $tanggal = $item->tanggal_input;
            $day = date('d', strtotime($tanggal));
            // Cek apakah ada perubahan data penghasilan
            $total_penyesuaian = 0;
            if ($item->status == 'proses') {
                $data_gaji = DB::table('gaji_per_bulan AS gaji')
                                ->select(
                                    'gaji.*',
                                    'm.nama_karyawan',
                                    DB::raw('CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) AS SIGNED) AS total_penghasilan'),
                                    DB::raw('CAST((gaji.kredit_koperasi + gaji.iuran_koperasi + gaji.kredit_pegawai + gaji.iuran_ik) AS SIGNED) AS total_potongan')
                                )
                                ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                                ->where('gaji.batch_id', $item->id)
                                ->get();

                foreach ($data_gaji as $gaji) {
                    $tahun = $gaji->tahun;
                    $bulan = $gaji->bulan;
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
                                            ->whereYear('tanggal', $gaji->tahun)
                                            ->where(function($query) use ($tahun, $bulan, $tanggal, $day, $kd_entitas) {
                                                if ($bulan > 1) {
                                                    // Tanggal penggajian bulan sebelumnya
                                                    $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                                    $query->whereBetween('tanggal', [$start_date, $tanggal]);
                                                }
                                                else if ($bulan == 12) {
                                                    $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                                    $last_day = getLastDateOfMonth($tahun, $bulan);
                                                    $end_date = $tahun.'-'.$bulan.'-'.$last_day;
                                                    $query->whereBetween('tanggal', [$start_date, $end_date]);
                                                }
                                                else {
                                                    $query->whereDay('tanggal', '<=', $day);
                                                }
                                            })
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

                    // Get Penghasilan Tidak Rutin
                    $penghasilanTidakRutin = DB::table('penghasilan_tidak_teratur')
                                                ->select('id', 'id_tunjangan', 'nominal')
                                                ->where('nip', $gaji->nip)
                                                ->where('tahun', (int) $gaji->tahun)
                                                ->where(function($query) use ($tahun, $bulan, $tanggal, $day, $kd_entitas) {
                                                    if ($bulan > 1) {
                                                        // Tanggal penggajian bulan sebelumnya
                                                        $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                                        $query->whereBetween('created_at', [$start_date, $tanggal]);
                                                    }
                                                    else if ($bulan == 12) {
                                                        $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                                        $last_day = getLastDateOfMonth($tahun, $bulan);
                                                        $end_date = $tahun.'-'.$bulan.'-'.$last_day;
                                                        $query->whereBetween('created_at', [$start_date, $end_date]);
                                                    }
                                                    else {
                                                        $query->whereDay('created_at', '<=', $day);
                                                    }
                                                })
                                                ->get();
                    foreach ($penghasilanTidakRutin as $tidakRutin) {
                        $current = DB::table('batch_penghasilan_tidak_teratur')
                                    ->where('gaji_per_bulan_id', $gaji->id)
                                    ->where('penghasilan_tidak_teratur_id', $tidakRutin->id)
                                    ->first();
                        if ($current) {
                            $nominalLama = $current->nominal;
                            $nominalBaru = $tidakRutin->nominal;
                            if ($nominalLama != $nominalBaru) {
                                $total_penyesuaian++;
                            }
                        }
                        else {
                            $total_penyesuaian++;
                        }
                    }

                    // Get Batch Penghasilan Tidak Rutin
                    $batchPenghasilanTidakRutin = DB::table('batch_penghasilan_tidak_teratur AS p')
                                                    ->select(
                                                        'p.id',
                                                        'p.penghasilan_tidak_teratur_id',
                                                        'p.id_tunjangan',
                                                        'm.nama_tunjangan',
                                                        'p.nominal',
                                                    )
                                                    ->join('mst_tunjangan AS m', 'm.id', 'p.id_tunjangan')
                                                    ->where('p.gaji_per_bulan_id', $gaji->id)
                                                    ->get();
                    foreach ($batchPenghasilanTidakRutin as $batchTidakRutin) {
                        $current = DB::table('penghasilan_tidak_teratur')
                                        ->where('id', $batchTidakRutin->penghasilan_tidak_teratur_id)
                                        ->first();
                        if (!$current) {
                            $total_penyesuaian++;
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
    public function getPenghasilanTrash($tahun, $bulan, $cabang, $status, $limit=10, $page = 1, $search) {
        $is_cabang = auth()->user()->hasRole('cabang');
        $is_pusat = auth()->user()->hasRole('kepegawaian');
        $data = DB::table('batch_gaji_per_bulan AS batch')
                ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
                ->join('pph_yang_dilunasi AS pph', 'pph.gaji_per_bulan_id', 'gaji.id')
                ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                ->leftJoin('mst_divisi AS md', 'md.kd_divisi', 'm.kd_entitas')
                ->join('mst_cabang AS cab', 'cab.kd_cabang', 'batch.kd_entitas')
                ->select(
                    'batch.id',
                    'batch.kd_entitas',
                    'batch.is_pegawai',
                    'cab.nama_cabang AS kantor',
                    'batch.tanggal_input',
                    'batch.tanggal_final',
                    'batch.tanggal_cetak',
                    'batch.tanggal_upload',
                    'batch.file',
                    'batch.status',
                    'gaji.bulan',
                    'gaji.tahun',
                    DB::raw('CAST(SUM(pph.total_pph) AS SIGNED) AS total_pph'),
                    DB::raw('CAST(SUM(pph.insentif_kredit + pph.insentif_penagihan) AS SIGNED) AS total_pajak_insentif'),
                    DB::raw('CAST(SUM(pph.total_pph) - SUM(pph.insentif_kredit + pph.insentif_penagihan) AS SIGNED) AS hasil_pph'),
                    DB::raw('CAST(SUM(gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) AS SIGNED) AS bruto'),
                    DB::raw('CAST(SUM(gaji.kredit_koperasi + gaji.iuran_koperasi + gaji.kredit_pegawai + gaji.iuran_ik) AS SIGNED) AS total_potongan'),
                    'm.kd_entitas AS entitas_karyawan',
                    'md.nama_divisi',
                )
                ->where(function ($query) use ($search) {
                    $query->where('gaji.tahun', 'like', "%$search%")
                        ->orWhere('cab.nama_cabang', 'like', "%$search%");
                })
                ->where(function ($query) use ($cabang) {
                    if ($cabang != null) {
                        $query->where('batch.kd_entitas', $cabang);
                    }
                })
                ->where(function ($query) use ($tahun) {
                    if ($tahun) {
                        $query->whereYear('batch.tanggal_input', $tahun);
                    }
                })
                ->where(function ($query) use ($bulan) {
                    if ($bulan) {
                        $query->whereMonth('batch.tanggal_input', $bulan);
                    }
                })
                ->when($is_cabang, function($query) {
                    $kd_cabang = auth()->user()->kd_cabang;
                    $query->where('batch.kd_entitas', $kd_cabang);
                })
                ->when($is_pusat, function($query) {
                    $query->where('batch.kd_entitas', '000');
                })
                ->whereNotNull('batch.deleted_at')
                ->orderBy('gaji.created_at', 'desc')
                ->groupBy('batch.id')
                ->groupBy('batch.kd_entitas')
                ->groupBy('gaji.bulan')
                ->groupBy('gaji.tahun')
                ->paginate($limit,['*'], 'page', $page);

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
                            'gaji.dpp',
                            'gaji.jp',
                            'gaji.bpjs_tk',
                            'gaji.penambah_bruto_jamsostek',
                            DB::raw('CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional + gaji.tj_transport + gaji.tj_pulsa + gaji.tj_vitamin + gaji.uang_makan) AS SIGNED) AS gaji'),
                            DB::raw("CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_jabatan + tj_teller + gaji.tj_perumahan + gaji.tj_telepon + gaji.tj_pelaksana + gaji.tj_kemahalan + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) AS SIGNED) AS total_gaji"),
                        )
                        ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                        ->where('gaji.batch_id', $item->id)
                        ->get();
            $total_dpp = 0;
            $total_bpjs_tk = 0;
            foreach ($gaji_obj as $value) {
                $gaji = $value->gaji;
                $total_gaji = $value->total_gaji;
                $dpp = $value->dpp;
                $total_dpp += $dpp;
                $jp_1_persen = $value->jp;

                // Get BPJS TK
                $bpjs_tk = $value->bpjs_tk;
                $total_bpjs_tk += $bpjs_tk;
            }
            $grand_total_potongan = ($item->total_potongan + floor($total_bpjs_tk) + floor($total_dpp));
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
                                    DB::raw('CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) AS SIGNED) AS total_penghasilan'),
                                    DB::raw('CAST((gaji.kredit_koperasi + gaji.iuran_koperasi + gaji.kredit_pegawai + gaji.iuran_ik) AS SIGNED) AS total_potongan')
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

    public static function getLastPenggajianCurrentYear($kd_entitas, $is_pegawai = true) {
        // Gaji bulan sebelumnya
        $batch = DB::table('batch_gaji_per_bulan AS batch')
            ->where('kd_entitas', $kd_entitas)
            ->where('is_pegawai', $is_pegawai)
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->first('tanggal_input');
        return $batch;
    }

    public static function getDivisiNonPegawai() {
        $orderRaw = "
            CASE
            WHEN kd_jabatan='DIRUT' THEN 1
            WHEN kd_jabatan='DIRUMK' THEN 2
            WHEN kd_jabatan='DIRPEM' THEN 3
            WHEN kd_jabatan='DIRHAN' THEN 4
            WHEN kd_jabatan='KOMU' THEN 5
            WHEN kd_jabatan='KOM' THEN 7
            WHEN kd_jabatan='STAD' THEN 8
            WHEN kd_jabatan='PIMDIV' THEN 9
            WHEN kd_jabatan='PSD' THEN 10
            WHEN kd_jabatan='PC' THEN 11
            WHEN kd_jabatan='PBP' THEN 12
            WHEN kd_jabatan='PBO' THEN 13
            WHEN kd_jabatan='PEN' THEN 14
            WHEN kd_jabatan='ST' THEN 15
            WHEN kd_jabatan='NST' THEN 16
            WHEN kd_jabatan='IKJP' THEN 17 END ASC
        ";
        $kd_jabatan = DB::table('mst_jabatan')
                    ->select('kd_jabatan', 'nama_jabatan')
                    ->where('nama_jabatan', 'like', '%Direktur%')
                    ->orWhere('nama_jabatan', 'like', '%Komisaris%')
                    ->orWhere('nama_jabatan', 'like', '%Ahli%')
                    ->orderByRaw($orderRaw)
                    ->get();
        return $kd_jabatan;
    }
}
