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

            }
            $karyawan->jamsostek = $jamsostek;
            $karyawan->bpjs_tk = $bpjs_tk;
            $karyawan->bpjs_kesehatan = $bpjs_kesehatan;
            $karyawan->potongan = $potongan;
            // Get total yg diterima
            if ($karyawan->potonganGaji) {
                $total_potongan += $karyawan->potonganGaji->total_potongan;
            }

            if ($karyawan->potongan) {
                $total_potongan += $karyawan->potongan->dpp;
            }
            
            $total_potongan += $bpjs_tk;

            $total_yg_diterima = $total_gaji - $total_potongan;
            $karyawan->total_yg_diterima = $total_yg_diterima;
            $karyawan->total_potongan = $total_potongan;
        }
        return $data;
    }
}