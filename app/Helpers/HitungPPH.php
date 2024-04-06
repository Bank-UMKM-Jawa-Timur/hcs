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
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereMonth('created_at', (int) $bulan)
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
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereMonth('created_at', (int) $bulan)
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
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereMonth('created_at', (int) $bulan)
                                            ->sum('nominal');
            }
            else {
                $penghasilanTidakRutin += DB::table('penghasilan_tidak_teratur')
                                            ->select('nominal')
                                            ->where('nip', $karyawan->nip)
                                            ->whereYear('created_at', (int) $tahun)
                                            ->whereMonth('created_at', (int) $bulan)
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

    public static function calcPPH58($bulan, $tahun, $ptkp, $bruto) {
        $bruto = str_replace('.', '', $bruto);
        $bruto = str_replace(',', '.', $bruto);
        $bruto = floatval($bruto);

        $kode_ptkp = $ptkp;
        $ter_kategori = HitungPPH::getTarifEfektifKategori($kode_ptkp);
        $lapisanPenghasilanBruto = DB::table('lapisan_penghasilan_bruto')
                                        ->where('kategori', $ter_kategori)
                                        ->where(function($query) use ($bruto) {
                                            $query->where(function($q2) use ($bruto) {
                                                $q2->where('nominal_start', '<=', $bruto)
                                                    ->where('nominal_end', '>=', $bruto);
                                            })
                                            ->orWhere(function($q2) use ($bruto) {
                                                $q2->where('nominal_start', '<=', $bruto)
                                                    ->where('nominal_end', 0);
                                            });
                                        })
                                        ->first();
        $pengali = 0;
        if ($lapisanPenghasilanBruto) {
            $pengali = $lapisanPenghasilanBruto->pengali;
        }

        $pph = $bruto * ($pengali / 100);
        $pph = $pph;
        $result = [
            'pengali' => ($pengali / 100),
            'pph' => number_format($pph, 2, ',', '.'),
            'pph_floor' => number_format(floor($pph), 2, ',', '.'),
        ];

        return $result;
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

        $new_pph = HitungPPH::getPPh58($bulan, $tahun, $karyawan, $ptkp, $tanggal_input, $total_gaji, $tunjangan_rutin);

        DB::table('pph_yang_dilunasi')
            ->where('nip', $karyawan->nip)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->update([
                'total_pph' => $new_pph,
                'updated_at' => now(),
            ]);


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
            $jkk = 0;
            $jht = 0;
            $jkm = 0;
            $jp_penambah = 0;
            $bpjs_kesehatan = 0;
            $jamsostek = 0;
            if($karyawan->kpj){
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
}
