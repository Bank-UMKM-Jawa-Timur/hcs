<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class GajiPerBulanRepository
{
    public function getPenghasilanList($status, $search, $limit=10) {
        $is_cabang = auth()->user()->hasRole('cabang');
        $is_pusat = auth()->user()->hasRole('kepegawaian');
        $kd_cabang = DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();
        $year = date('Y');

        $data = DB::table('batch_gaji_per_bulan AS batch')
                ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
                ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                ->join('mst_cabang AS cab', 'cab.kd_cabang', 'batch.kd_entitas')
                ->select(
                    'batch.id',
                    'cab.nama_cabang AS kantor',
                    'batch.tanggal_input',
                    'batch.tanggal_final',
                    'batch.tanggal_cetak',
                    'batch.file',
                    'batch.status',
                    'gaji.bulan',
                    'gaji.tahun',
                    DB::raw('CAST(SUM(gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti) AS UNSIGNED) AS bruto'),
                    DB::raw('CAST(SUM(gaji.kredit_koperasi + gaji.iuran_koperasi + gaji.kredit_pegawai + gaji.iuran_ik) AS UNSIGNED) AS total_potongan')
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
                ->whereYear('batch.tanggal_input', $year)
                ->orderBy('gaji.created_at', 'desc')
                ->groupBy('batch.kd_entitas')
                ->groupBy('gaji.bulan')
                ->groupBy('gaji.tahun')
                ->paginate($limit);

        foreach ($data as $key => $item) {
            // Get Bruto & Netto
            $netto = 0;
            $netto = $item->bruto - $item->total_potongan;
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