<?php

namespace App\Helpers;

use App\Repository\CabangRepository;
use Illuminate\Support\Facades\DB;
use stdClass;

class CheckHitungPPH
{
    public static function getPPh58($bulan, $tahun, $karyawan, $ptkp, $tanggal, $total_gaji, $tunjangan_rutin = 0, $full_month = false, $is_delete=false) {
        // Get Kode entitas
        $kd_entitas = '000';
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);
        if ($karyawan->kd_entitas) {
            if (in_array($karyawan->kd_entitas, $kode_cabang_arr)) {
                $kd_entitas = $karyawan->kd_entitas;
            }
        }
        $penghasilanRutin = 0;
        $penghasilanTidakRutin = 0;
        $penghasilanTidakRutinFull = 0;
        $penghasilanTidakRutinBrutoInsentif = 0;
        $penghasilanBruto = 0;
        $penghasilanBrutoAkhirBulan = 0;

        $idTunjInsentifArr = [31, 32];
        // Get total penghasilan rutin
        $penghasilanRutin = $total_gaji;
        $tanggal_filter = $tahun . '-' . $bulan . '-' . '25';
        // Get total penghasilan tidak rutin
        if ($bulan > 1) {
            // Feb - Nov
            if ($full_month) {
                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereMonth('created_at', (int) $bulan)
                                            ->sum('nominal');
                $penghasilanTidakRutinBrutoInsentif = $penghasilanTidakRutin;
                $total_insentif = (int) DB::table('penghasilan_tidak_teratur')
                                    ->whereIn('id_tunjangan', [31, 32])
                                    ->whereYear('created_at', (int) $tahun)
                                    ->whereMonth('created_at', (int) $bulan)
                                    ->where('nip', $karyawan->nip)
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

                $penghasilanTidakRutinBrutoInsentif += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereBetween('created_at', [$start_date, $tanggal])
                                            ->sum('nominal');
                $total_insentif = (int) DB::table('penghasilan_tidak_teratur')
                                    ->whereIn('id_tunjangan', [31, 32])
                                    ->where('nip', $karyawan->nip)
                                    ->whereBetween('created_at', [$start_date, $tanggal])
                                    ->sum('nominal');
            }

        }
        else if ($bulan == 12) {
            // Des
            if ($full_month) {
                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereMonth('created_at', (int) $bulan)
                                            ->sum('nominal');
                $penghasilanTidakRutinBrutoInsentif = $penghasilanTidakRutin;
                $total_insentif = (int) DB::table('penghasilan_tidak_teratur')
                                    ->whereIn('id_tunjangan', [31, 32])
                                    ->whereYear('created_at', (int) $tahun)
                                    ->whereMonth('created_at', (int) $bulan)
                                    ->where('nip', $karyawan->nip)
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
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereBetween('created_at', [$start_date, $end_date])
                                            ->sum('nominal');
                $penghasilanTidakRutinBrutoInsentif += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereBetween('created_at', [$start_date, $end_date])
                                            ->sum('nominal');
                $total_insentif = (int) DB::table('penghasilan_tidak_teratur')
                                            ->whereIn('id_tunjangan', [31, 32])
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereMonth('created_at', (int) $bulan)
                                            ->whereBetween('created_at', [$start_date, $end_date])
                                            ->where('nip', $karyawan->nip)
                                            ->sum('nominal');
            }
        }
        else {
            // Jan
            if ($full_month) {
                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereMonth('created_at', (int) $bulan)
                                            ->whereNotIn('id_tunjangan', $idTunjInsentifArr)
                                            ->sum('nominal');
                $penghasilanTidakRutinBrutoInsentif = $penghasilanTidakRutin;
                $total_insentif = (int) DB::table('penghasilan_tidak_teratur')
                                    ->whereIn('id_tunjangan', [31, 32])
                                    ->whereYear('created_at', (int) $tahun)
                                    ->whereMonth('created_at', (int) $bulan)
                                    ->where('nip', $karyawan->nip)
                                    ->sum('nominal');
            }
            else {
                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($tanggal)))
                                            ->whereNotIn('id_tunjangan', $idTunjInsentifArr)
                                            ->sum('nominal');
                $penghasilanTidakRutinBrutoInsentif += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereMonth('created_at', (int) $bulan)
                                            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($tanggal)))
                                            ->sum('nominal');

                $total_insentif = (int) DB::table('penghasilan_tidak_teratur')
                                    ->whereIn('id_tunjangan', [31, 32])
                                    ->whereYear('created_at', (int) $tahun)
                                    ->where('nip', $karyawan->nip)
                                    ->whereDate('created_at', '<=', date('Y-m-d', strtotime($tanggal)))
                                    ->sum('nominal');
            }
        }

        // bruto akhir bulan
        $tanggal_filter_full_awal = $tahun . '-' . $bulan . '-' . '26';
        $tanggal_filter_full_akhir = $tahun . '-' . $bulan . '-' . '31';
        $penghasilanTidakRutinFull += DB::table('penghasilan_tidak_teratur')
                                    ->select('nominal')
                                    ->where('nip', $karyawan->nip)
                                    ->whereYear('created_at', (int) $tahun)
                                    ->whereMonth('created_at', (int) $bulan)
                                    ->whereBetween('created_at', [$tanggal_filter_full_awal, $tanggal_filter_full_akhir])
                                    ->whereNotIn('id_tunjangan', $idTunjInsentifArr)
                                    ->sum('nominal');

        $tgl_awal_1 = $tahun . '-' . $bulan . '-' . '1';
        $tgl_akhir_25 = $tahun . '-' . $bulan . '-' . '25';
        $total_insentif_25 = DB::table('penghasilan_tidak_teratur')
                                ->whereIn('id_tunjangan', [31, 32])
                                ->where('nip', $karyawan->nip)
                                ->whereBetween('created_at', [$tgl_awal_1, $tgl_akhir_25])
                                ->sum('nominal');
        $total_insentif_26 = DB::table('penghasilan_tidak_teratur')
                                ->whereIn('id_tunjangan', [31, 32])
                                ->where('nip', $karyawan->nip)
                                ->whereBetween('created_at', [$tanggal_filter_full_awal, $tanggal_filter_full_akhir])
                                ->sum('nominal');
        $dppJamsostek = CheckHitungPPH::getJamsostekDPP($karyawan, $total_gaji);
        
        $jamsostek = $dppJamsostek['jamsostek'];
        $penghasilanBruto = $penghasilanRutin + $penghasilanTidakRutin + $jamsostek + $tunjangan_rutin;
        $penghasilanBrutoAkhirBulan = $penghasilanRutin + $penghasilanTidakRutin + $penghasilanTidakRutinFull + $jamsostek + $tunjangan_rutin;
        $penghasilanBrutoBaru = $penghasilanRutin + $penghasilanTidakRutinBrutoInsentif + $jamsostek + $tunjangan_rutin; // Bruto yg telah dibebankan pada bulan selanjutnya (include insentif)
        $penghasilanBrutoDb = $penghasilanBruto + $total_insentif;

        $penghasilanBrutoAkhirBulanNonInsetif = $penghasilanBrutoAkhirBulan;
        $pph = 0;
        $pphBentukanBaru = 0;
        $pphAkhirBulan = 0;

        $kode_ptkp = $ptkp->kode;
        $ter_kategori = CheckHitungPPH::getTarifEfektifKategori($kode_ptkp);
        $lapisanPenghasilanBrutoDB = DB::table('lapisan_penghasilan_bruto')
                                    ->where('kategori', $ter_kategori)
                                    ->where(function($query) use ($penghasilanBrutoDb) {
                                        $query->where(function($q2) use ($penghasilanBrutoDb) {
                                            $q2->where('nominal_start', '<=', $penghasilanBrutoDb)
                                                ->where('nominal_end', '>=', $penghasilanBrutoDb);
                                        })->orWhere(function($q2) use ($penghasilanBrutoDb) {
                                            $q2->where('nominal_start', '<=', $penghasilanBrutoDb)
                                                ->where('nominal_end', 0);
                                        });
                                    })
                                    ->first();
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
        $lapisanPenghasilanBrutoBaru = DB::table('lapisan_penghasilan_bruto')
                                    ->where('kategori', $ter_kategori)
                                    ->where(function($query) use ($penghasilanBrutoBaru) {
                                        $query->where(function($q2) use ($penghasilanBrutoBaru) {
                                            $q2->where('nominal_start', '<=', $penghasilanBrutoBaru)
                                                ->where('nominal_end', '>=', $penghasilanBrutoBaru);
                                        })->orWhere(function($q2) use ($penghasilanBrutoBaru) {
                                            $q2->where('nominal_start', '<=', $penghasilanBrutoBaru)
                                                ->where('nominal_end', 0);
                                        });
                                    })
                                    ->first();
        $lapisanPenghasilanBrutoAkhir = DB::table('lapisan_penghasilan_bruto')
                                    ->where('kategori', $ter_kategori)
                                    ->where(function($query) use ($penghasilanBrutoAkhirBulanNonInsetif) {
                                        $query->where(function($q2) use ($penghasilanBrutoAkhirBulanNonInsetif) {
                                            $q2->where('nominal_start', '<=', $penghasilanBrutoAkhirBulanNonInsetif)
                                                ->where('nominal_end', '>=', $penghasilanBrutoAkhirBulanNonInsetif);
                                        })->orWhere(function($q2) use ($penghasilanBrutoAkhirBulanNonInsetif) {
                                            $q2->where('nominal_start', '<=', $penghasilanBrutoAkhirBulanNonInsetif)
                                                ->where('nominal_end', 0);
                                        });
                                    })
                                    ->first();

        $pengali = 0;
        $pengaliDb = 0;
        $pengali_akhir = 0;
        $pengali_baru = 0;
        if ($lapisanPenghasilanBrutoDB) {
            $pengaliDb = $lapisanPenghasilanBrutoDB->pengali;
        }
        if ($lapisanPenghasilanBruto) {
            $pengali = $lapisanPenghasilanBruto->pengali;
        }
        if ($lapisanPenghasilanBrutoBaru) {
            $pengali_baru = $lapisanPenghasilanBrutoBaru->pengali;
        }
        if ($lapisanPenghasilanBrutoAkhir) {
            $pengali_akhir = $lapisanPenghasilanBrutoAkhir->pengali;
        }

        $pph = $penghasilanBruto * ($pengali / 100);
        $pph = floor($pph);
        $pphBentukanBaru = $penghasilanBrutoBaru * ($pengali_baru / 100);
        $pphBentukanBaru = floor($pphBentukanBaru);
        $pphAkhirBulan = $penghasilanBrutoAkhirBulanNonInsetif * ($pengali_akhir / 100);
        $pphAkhirBulan = floor($pphAkhirBulan);
        // if ($karyawan->nip == '00906') {
        //     dd($total_insentif);
        // }
        $potongan = DB::table('potongan_gaji AS p')
                        ->select(
                            DB::raw('CAST(p.kredit_pegawai AS SIGNED) AS kredit_pegawai'),
                            DB::raw('CAST(p.kredit_koperasi AS SIGNED) AS kredit_koperasi'),
                            DB::raw('CAST(p.iuran_koperasi AS SIGNED) AS iuran_koperasi'),
                            DB::raw('CAST(p.iuran_ik AS SIGNED) AS iuran_ik'),
                            DB::raw('(CAST(p.kredit_pegawai AS SIGNED) + CAST(p.kredit_koperasi AS SIGNED) + CAST(p.iuran_koperasi AS SIGNED) + CAST(p.iuran_ik AS SIGNED)) AS total_potongan'),
                        )
                        ->where('nip', $karyawan->nip)
                        ->first();

        $seharusnya = DB::table('pph_yang_dilunasi as pd')
                        ->select(
                            DB::raw('(CAST(pd.total_pph AS SIGNED) - (CAST(pd.insentif_kredit AS SIGNED) + CAST(pd.insentif_penagihan AS SIGNED))) AS total_seharusnya'),
                            DB::raw('CAST(pd.total_pph AS SIGNED) AS pph_bentukan'),
                            DB::raw('(CAST(pd.insentif_kredit AS SIGNED) + CAST(pd.insentif_penagihan AS SIGNED)) AS total_insentif'),
                            DB::raw('CAST(pd.terutang AS SIGNED) - CAST(pd.terutang_insentif AS SIGNED) AS terutang'),
                        )
                        ->join('gaji_per_bulan AS gaji', 'gaji.id', 'pd.gaji_per_bulan_id')
                        ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                        ->where(function($query) use ($is_delete) {
                            if ($is_delete) {
                                $query->whereNotNull('batch.deleted_at');
                            }
                            else {
                                $query->whereNull('batch.deleted_at');
                            }
                        })
                        ->where('pd.nip', $karyawan->nip)
                        ->where('pd.bulan', $bulan)
                        ->where('pd.tahun', $tahun)
                        ->first();

        $data = new stdClass;
        $data->nama = $karyawan->nama_karyawan;
        $data->nip = $karyawan->nip;
        $data->ptkp = $ptkp;
        $data->total_insentif_baru = (int) $total_insentif;
        $data->total_insentif_25 = (int) $total_insentif_25;
        $data->total_insentif_26 = (int) $total_insentif_26;
        $data->pajak_insentif_25 = floor(($total_insentif_25 * config('global.pengali_insentif_kredit')));
        $data->pajak_insentif_26 = floor(($total_insentif_26 * config('global.pengali_insentif_kredit')));
        $data->pajak_insentif_baru = floor(($total_insentif * 0.05));
        $data->penghasilanRutin = $penghasilanRutin;
        $data->penghasilanTidakRutin = $penghasilanTidakRutin;
        $data->jamsostek = $dppJamsostek['jamsostek'];
        $data->penghasilanBruto = $penghasilanBruto;
        $data->penghasilanBrutoInsentif = $penghasilanBrutoDb;
        $data->penghasilanBrutoBaru = $penghasilanBrutoBaru;
        $data->penghasilanBrutoAkhirBulan = $penghasilanBrutoAkhirBulan;
        $data->tunjangan = $tunjangan_rutin;
        $data->potongan = $potongan;
        $data->dpp = $dppJamsostek['dpp'];
        $data->bpjs_tk = $dppJamsostek['bpjs_tk'];
        $data->pengali_db = ($pengaliDb / 100);
        $data->pengali = ($pengali / 100);
        $data->seharusnya = $seharusnya;
        $data->pengali_baru = ($pengali_baru / 100);
        $data->pengali_akhir = ($pengali_akhir / 100);
        $data->pph_bentukan = $pph;
        $data->pph_bentukan_baru = $pphBentukanBaru;
        $data->total_insentif = $seharusnya?->total_insentif;
        $data->pph = $pph - $seharusnya?->total_insentif;
        $data->pph_akhir_bulan = $pphAkhirBulan - $seharusnya?->total_insentif;

        return $data;
    }

    public static function checkPPH58($tanggal, $bulan, $tahun, $karyawan) {
        $gaji = DB::table('gaji_per_bulan AS gaji')
                            ->select(
                                'gaji.id',
                                'batch.tanggal_input',
                                DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_teller + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji"),
                                DB::raw("(uang_makan + tj_transport + tj_pulsa + tj_vitamin) AS tunjangan_rutin"),
                                DB::raw('CAST(pph.insentif_kredit AS SIGNED) AS insentif_kredit'),
                                DB::raw('CAST(pph.insentif_penagihan AS SIGNED) AS insentif_penagihan'),
                                DB::raw('CAST(pph.total_pph AS SIGNED) AS pph'),
                                DB::raw('CAST(pph.terutang AS SIGNED) AS terutang'),
                                DB::raw('CAST(pph.terutang_insentif AS SIGNED) AS terutang_insentif'),
                            )
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                            ->join('pph_yang_dilunasi AS pph', 'pph.gaji_per_bulan_id', 'gaji.id')
                            ->where('gaji.nip', $karyawan->nip)
                            ->where('gaji.bulan', (int) $bulan)
                            ->where('gaji.tahun', (int) $tahun)
                            ->first();
        $gaji_id = 0;
        $tanggal_input = $tahun.'-'.$bulan.'-'.'25';
        $total_gaji = 0;
        $tunjangan_rutin = 0;
        $pph_db = 0;
        $terutang_db = 0;
        $terutang_insentif_db = 0;
        $pajak_insentif_kredit_db = 0;
        $pajak_insentif_penagihan_db = 0;
        if ($gaji) {
            $gaji_id = $gaji->id;
            $tanggal_input = $gaji->tanggal_input;
            $total_gaji = $gaji->total_gaji;
            $tunjangan_rutin = $gaji->tunjangan_rutin;
            $pph_db = $gaji->pph;
            $terutang_db = $gaji->terutang;
            $terutang_insentif_db = $gaji->terutang_insentif;
            $pajak_insentif_kredit_db = $gaji->insentif_kredit;
            $pajak_insentif_penagihan_db = $gaji->insentif_penagihan;
        }

        $pph_db -= ($pajak_insentif_kredit_db + $pajak_insentif_penagihan_db);
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
        $ptkp = CheckHitungPPH::getPTKP($karyawan);

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

        $penghasilanRutin = new stdClass;
        $penghasilanTidakRutin = new stdClass;
        $potongan = new stdClass;
        $bruto = 0;
        $new_pph = CheckHitungPPH::getPPh58($bulan, $tahun, $karyawan, $ptkp, $tanggal_input, $total_gaji, $tunjangan_rutin, $full_month);

        $data = [
            'pph_db' => $pph_db,
            'terutang_db' => $terutang_db,
            'terutang_insentif_db' => $terutang_insentif_db,
            'pph' => $new_pph,
        ];
        return $data;
    }

    public static function newCheckPPH58($tanggal, $bulan, $tahun, $karyawan) {
        $old = CheckHitungPPH::getPenyesuaianPPH($tanggal, $bulan, $tahun, $karyawan, true);
        $new = CheckHitungPPH::getPenyesuaianPPH($tanggal, $bulan, $tahun, $karyawan, false);

        return compact('old', 'new');
    }

    public static function getPenyesuaianPPH($tanggal, $bulan, $tahun, $karyawan, $is_delete) {
        $gaji = DB::table('gaji_per_bulan AS gaji')
                            ->select(
                                'gaji.id',
                                'batch.tanggal_input',
                                DB::raw("(gj_pokok + gj_penyesuaian + tj_keluarga + tj_jabatan + tj_perumahan + tj_telepon + tj_pelaksana + tj_kemahalan + tj_kesejahteraan + tj_teller + tj_multilevel + tj_ti + tj_fungsional) AS total_gaji"),
                                DB::raw("(uang_makan + tj_transport + tj_pulsa + tj_vitamin) AS tunjangan_rutin"),
                                DB::raw('CAST(pph.insentif_kredit AS SIGNED) AS insentif_kredit'),
                                DB::raw('CAST(pph.insentif_penagihan AS SIGNED) AS insentif_penagihan'),
                                DB::raw('CAST(pph.total_pph AS SIGNED) AS pph'),
                                DB::raw('CAST(pph.terutang AS SIGNED) AS terutang'),
                                DB::raw('CAST(pph.terutang_insentif AS SIGNED) AS terutang_insentif'),
                            )
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                            ->join('pph_yang_dilunasi AS pph', 'pph.gaji_per_bulan_id', 'gaji.id')
                            ->where(function($query) use ($is_delete) {
                                if ($is_delete)
                                    $query->whereNotNull('batch.deleted_at');
                                else
                                    $query->whereNull('batch.deleted_at');
                            })
                            ->where('gaji.nip', $karyawan->nip)
                            ->where('gaji.bulan', (int) $bulan)
                            ->where('gaji.tahun', (int) $tahun)
                            ->first();
        $gaji_id = 0;
        $tanggal_input = $tahun.'-'.$bulan.'-'.'25';
        $total_gaji = 0;
        $tunjangan_rutin = 0;
        $pph_db = 0;
        $terutang_db = 0;
        $terutang_insentif_db = 0;
        $pajak_insentif_kredit_db = 0;
        $pajak_insentif_penagihan_db = 0;
        if ($gaji) {
            $gaji_id = $gaji->id;
            $tanggal_input = $gaji->tanggal_input;
            $total_gaji = $gaji->total_gaji;
            $tunjangan_rutin = $gaji->tunjangan_rutin;
            $pph_db = $gaji->pph;
            $terutang_db = $gaji->terutang;
            $terutang_insentif_db = $gaji->terutang_insentif;
            $pajak_insentif_kredit_db = $gaji->insentif_kredit;
            $pajak_insentif_penagihan_db = $gaji->insentif_penagihan;
        }

        $pph_db -= ($pajak_insentif_kredit_db + $pajak_insentif_penagihan_db);
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

        // Get PTKP
        $ptkp = CheckHitungPPH::getPTKP($karyawan);

        $full_month = false;

        $new_pph = CheckHitungPPH::getPPh58($bulan, $tahun, $karyawan, $ptkp, $tanggal_input, $total_gaji, $tunjangan_rutin, $full_month, $is_delete);

        $data = [
            'pph_db' => $pph_db,
            'pph_final_db' => $pph_final,
            'terutang_db' => $terutang_db,
            'terutang_insentif_db' => $terutang_insentif_db,
            'pph' => $new_pph,
        ];

        return $data;
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

    public static function getJamsostekDPP($karyawan, $total_gaji) {
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
                            DB::raw('CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional + gaji.tj_transport + gaji.tj_pulsa + gaji.tj_vitamin + gaji.uang_makan) AS SIGNED) AS gaji'),
                            DB::raw("CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_jabatan + tj_teller + gaji.tj_perumahan + gaji.tj_telepon + gaji.tj_pelaksana + gaji.tj_kemahalan + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti + gaji.tj_fungsional) AS SIGNED) AS total_gaji"),
                        )
                        ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                        ->where('gaji.nip', $karyawan->nip)
                        ->first();

        // Get DPP & Jamsostek
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

        $id_tunjangan_teratur_arr = DB::table('mst_tunjangan')
                                    ->where('status', 1)
                                    ->where('kategori', 'teratur')
                                    ->pluck('id')
                                    ->toArray();
        $tunjangan = (int) DB::table('tunjangan_karyawan')
                                ->where('nip', $karyawan->nip)
                                ->whereIn('id_tunjangan', $id_tunjangan_teratur_arr)
                                ->sum('nominal');
        $dpp = 0;
        $bpjs_tk = 0;
        $jamsostek = 0;
        if($total_gaji > 0){
            $bruto_karyawan = $tunjangan + $karyawan->gj_pokok + $karyawan->gj_penyesuaian;

            $bpjs_kesehatan = 0;
            $jkk = 0;
            $jht = 0;
            $jkm = 0;
            $jp_penambah = 0;
            // DPP
            $nominal_jp = (date('m') > 2) ? $jp_mar_des : $jp_jan_feb;
            if($karyawan->status_karyawan == 'IKJP' || $karyawan->status_karyawan == 'Kontrak Perpanjangan') {
                $dpp = 0;
                $jp_1_persen = floor((($persen_jp_pengurang / 100) * $bruto_karyawan));
            } else{
                $gj_pokok = $gaji_obj->gj_pokok;
                $tj_keluarga = $gaji_obj->tj_keluarga;
                $tj_kesejahteraan = $gaji_obj->tj_kesejahteraan;

                // DPP (Pokok + Keluarga + Kesejahteraan 50%) * 5%
                $dpp = (($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100);
                if($bruto_karyawan >= $nominal_jp){
                    $jp_1_persen = floor($nominal_jp * ($persen_jp_pengurang / 100));
                } else {
                    $jp_1_persen = floor($bruto_karyawan * ($persen_jp_pengurang / 100));
                }
            }
            $dpp = floor($dpp);
            // BPJS TK
            if ($gaji_obj->bulan > 2) {
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
            $bpjs_tk = floor($bpjs_tk);
            // Jamsostek
            if(!$karyawan->tanggal_penonaktifan && $karyawan->kpj){
                $jkk = floor(($persen_jkk / 100) * $total_gaji);
                $jht = floor(($persen_jht / 100) * $total_gaji);
                $jkm = floor(($persen_jkm / 100) * $total_gaji);
                $jp_penambah = floor(($persen_jp_penambah / 100) * $total_gaji);
            }

            if($karyawan->jkn){
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

        return ['dpp' => $dpp, 'bpjs_tk' => $bpjs_tk, 'jamsostek' => $jamsostek];
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

    public static function getPajakInsentif($nip, $bulan, $tahun, int $nominal, $tipe = 'kredit') {
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
}
