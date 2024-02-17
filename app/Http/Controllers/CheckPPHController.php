<?php

namespace App\Http\Controllers;

use App\Helpers\CheckHitungPPH;
use App\Models\KaryawanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CheckPPHController extends Controller
{
    public function index(Request $request) {
        $orderRaw = "
            CASE
            WHEN mst_karyawan.kd_jabatan='DIRUT' THEN 1
            WHEN mst_karyawan.kd_jabatan='DIRUMK' THEN 2
            WHEN mst_karyawan.kd_jabatan='DIRPEM' THEN 3
            WHEN mst_karyawan.kd_jabatan='DIRHAN' THEN 4
            WHEN mst_karyawan.kd_jabatan='KOMU' THEN 5
            WHEN mst_karyawan.kd_jabatan='KOM' THEN 7
            WHEN mst_karyawan.kd_jabatan='STAD' THEN 8
            WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 9
            WHEN mst_karyawan.kd_jabatan='PSD' THEN 10
            WHEN mst_karyawan.kd_jabatan='PC' THEN 11
            WHEN mst_karyawan.kd_jabatan='PBP' THEN 12
            WHEN mst_karyawan.kd_jabatan='PBO' THEN 13
            WHEN mst_karyawan.kd_jabatan='PEN' THEN 14
            WHEN mst_karyawan.kd_jabatan='ST' THEN 15
            WHEN mst_karyawan.kd_jabatan='NST' THEN 16
            WHEN mst_karyawan.kd_jabatan='IKJP' THEN 17 END ASC
        ";
        $tanggal = date('Y-m-d', strtotime("2024-01-25"));
        $bulan = (int) date('m', strtotime($tanggal));
        $tahun = (int) date('Y', strtotime($tanggal));
        $kd_entitas = $request->get('kd_entitas');
        $kd_cabang = \DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();
        $entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
        $cabang = \DB::table('mst_cabang')
                        ->select('kd_cabang', 'nama_cabang')
                        ->orderBy('kd_cabang')
                        ->get();

        if (auth()->user()->hasRole('cabang')) {
            $karyawan = KaryawanModel::whereNull('tanggal_penonaktifan')
                ->where('kd_entitas', auth()->user()->kd_cabang)
                ->orderByRaw($orderRaw)
                ->get();
        } else {
            if ($request->has('kd_entitas')) {
                if ($kd_entitas == '000') {
                    $karyawan = KaryawanModel::whereNull('tanggal_penonaktifan')
                                            ->where(function($query) use ($kd_cabang) {
                                                $query->whereNotIn('kd_entitas', $kd_cabang)
                                                    ->orWhereNull('kd_entitas');
                                            })
                                            ->orderByRaw($orderRaw)
                                            ->get();
                }
                else {
                    $karyawan = KaryawanModel::whereNull('tanggal_penonaktifan')
                                            ->where('kd_entitas', $kd_entitas)
                                            ->orderByRaw($orderRaw)
                                            ->get();
                }
            } else {
                $karyawan = KaryawanModel::whereNull('tanggal_penonaktifan')
                ->where(function ($query) use ($kd_cabang) {
                    $query->whereNotIn('kd_entitas', $kd_cabang)
                    ->orWhereNull('kd_entitas');
                })
                    ->orderByRaw($orderRaw)
                    ->get();
            }
        }
        $result = [];
        // if ($request->has('kd_entitas')) {
            foreach ($karyawan as $key => $value) {
                $data = CheckHitungPPH::checkPPH58($tanggal, $bulan, $tahun, $value);
                array_push($result, $data);
            }
        // }

        if (auth()->user()->hasRole('cabang')) {
            $dataC = DB::table('mst_cabang')->where('kd_cabang', auth()->user()->kd_cabang)->first();
            $nama_cabang = $dataC->nama_cabang;

        } else {
            if ($request->has('kd_entitas')) {
                $dataC = DB::table('mst_cabang')->where('kd_cabang', $request->get('kd_entitas'))->first();
                $nama_cabang = $dataC->nama_cabang;
            } else {
                $nama_cabang = "Pusat";
            }
        }

        return view('cek-pph-new', compact('cabang', 'result', 'nama_cabang', 'bulan', 'tahun'));
    }

    public function index25(Request $request) {
        $orderRaw = "
            CASE
            WHEN mst_karyawan.kd_jabatan='DIRUT' THEN 1
            WHEN mst_karyawan.kd_jabatan='DIRUMK' THEN 2
            WHEN mst_karyawan.kd_jabatan='DIRPEM' THEN 3
            WHEN mst_karyawan.kd_jabatan='DIRHAN' THEN 4
            WHEN mst_karyawan.kd_jabatan='KOMU' THEN 5
            WHEN mst_karyawan.kd_jabatan='KOM' THEN 7
            WHEN mst_karyawan.kd_jabatan='STAD' THEN 8
            WHEN mst_karyawan.kd_jabatan='PIMDIV' THEN 9
            WHEN mst_karyawan.kd_jabatan='PSD' THEN 10
            WHEN mst_karyawan.kd_jabatan='PC' THEN 11
            WHEN mst_karyawan.kd_jabatan='PBP' THEN 12
            WHEN mst_karyawan.kd_jabatan='PBO' THEN 13
            WHEN mst_karyawan.kd_jabatan='PEN' THEN 14
            WHEN mst_karyawan.kd_jabatan='ST' THEN 15
            WHEN mst_karyawan.kd_jabatan='NST' THEN 16
            WHEN mst_karyawan.kd_jabatan='IKJP' THEN 17 END ASC
        ";
        $tanggal = date('Y-m-d', strtotime("2024-01-25"));
        // $bulan = (int) date('m', strtotime($tanggal));
        // $tahun = (int) date('Y', strtotime($tanggal));
        $bulan = $request->has('bulan') ? (int) $request->get('bulan') : (int) date('m');
        $tahun = $request->has('tahun') ? (int) $request->get('tahun') : (int) date('Y');
        $kd_entitas = $request->get('kd_entitas');
        $kd_cabang = \DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();
        $entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
        $cabang = \DB::table('mst_cabang')
                        ->select('kd_cabang', 'nama_cabang')
                        ->orderBy('kd_cabang')
                        ->get();

        if (auth()->user()->hasRole('cabang')) {
            $karyawan = KaryawanModel::where('kd_entitas', auth()->user()->kd_cabang)
                                    ->whereRaw("(tanggal_penonaktifan IS NULL OR (MONTH(NOW()) = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                                    ->orderByRaw($orderRaw)
                                    ->get();
            $dataC = DB::table('mst_cabang')->where('kd_cabang', auth()->user()->kd_cabang)->first();
            $nama_cabang = $dataC->nama_cabang;
        } else {
            if ($request->has('kd_entitas')) {
                $batch = DB::table('batch_gaji_per_bulan')
                            ->where('kd_entitas', $request->get('kd_entitas'))
                            ->whereMonth('tanggal_input', $bulan)
                            ->whereYear('tanggal_input', $tahun)
                            ->orderByDesc('id')
                            ->first();
                $tanggal_penggajian = "2024-01-25";
                if ($batch) {
                    $tanggal_penggajian = $batch->tanggal_input;
                }
                $dataC = DB::table('mst_cabang')
                            ->where('kd_cabang', $request->get('kd_entitas'))
                            ->first();
                $nama_cabang = $dataC->nama_cabang;
                if ($kd_entitas == '000') {
                    $karyawan = DB::table('gaji_per_bulan')
                        ->select('mst_karyawan.*')
                        ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                        ->join('mst_karyawan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                        ->where(function ($query) use ($kd_cabang) {
                            $query->where('batch_gaji_per_bulan.kd_entitas', $kd_cabang);
                        })
                        ->where('gaji_per_bulan.tahun', $tahun)
                        ->where('gaji_per_bulan.bulan', $bulan)
                        ->whereNull('batch_gaji_per_bulan.deleted_at')
                        ->whereRaw("(tanggal_penonaktifan IS NULL OR (MONTH(NOW()) = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                        ->orderByRaw($orderRaw)
                        ->get();
                    // $karyawan = KaryawanModel::where(function($query) use ($kd_cabang) {
                    //                             $query->whereNotIn('kd_entitas', $kd_cabang)
                    //                                 ->orWhereNull('kd_entitas');
                    //                         })
                    //                         ->where(function($query) use ($tanggal_penggajian) {
                    //                             $query->whereNull('tanggal_penonaktifan')
                    //                                 ->orWhere(function($query2) use ($tanggal_penggajian) {
                    //                                     $bulan = date('m', strtotime($tanggal_penggajian));
                    //                                     $tahun = date('Y', strtotime($tanggal_penggajian));
                    //                                     $query2->where('tanggal_penonaktifan', '>=', $tanggal_penggajian)
                    //                                         ->orWhereMonth('tanggal_penonaktifan', $bulan)
                    //                                         ->whereYear('tanggal_penonaktifan', $tahun);
                    //                                 });
                    //                         })
                    //                         ->orderByRaw($orderRaw)
                    //                         ->get();
                }
                else {
                    $karyawan = DB::table('gaji_per_bulan')
                                    ->select('mst_karyawan.*')
                                    ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                                    ->join('mst_karyawan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                                    ->where('mst_karyawan.kd_entitas', $kd_entitas)
                                    ->where('gaji_per_bulan.tahun', $tahun)
                                    ->where('gaji_per_bulan.bulan', $bulan)
                                    ->whereNull('batch_gaji_per_bulan.deleted_at')
                                    ->whereRaw("(tanggal_penonaktifan IS NULL OR (MONTH(NOW()) = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                                    ->orderByRaw($orderRaw)
                                    ->get();
                }
            } else {
                $batch = DB::table('batch_gaji_per_bulan')
                            ->where('kd_entitas', $request->get('kd_entitas'))
                            ->whereMonth('tanggal_input', $bulan)
                            ->whereYear('tanggal_input', $tahun)
                            ->orderByDesc('id')
                            ->first();
                $tanggal_penggajian = "2024-01-25";
                if ($batch) {
                    $tanggal_penggajian = $batch->tanggal_input;
                }
                $karyawan = DB::table('gaji_per_bulan')
                    ->select('mst_karyawan.*')
                    ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                    ->join('mst_karyawan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                    ->where(function ($query) use ($kd_cabang) {
                        $query->where('batch_gaji_per_bulan.kd_entitas', $kd_cabang);
                    })
                    ->where('gaji_per_bulan.tahun', $tahun)
                    ->where('gaji_per_bulan.bulan', $bulan)
                    ->whereNull('batch_gaji_per_bulan.deleted_at')
                    ->whereRaw("(tanggal_penonaktifan IS NULL OR (MONTH(NOW()) = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                    ->orderByRaw($orderRaw)
                    ->get();
                // $karyawan = KaryawanModel::where(function ($query) use ($kd_cabang) {
                //                             $query->whereNotIn('kd_entitas', $kd_cabang)
                //                             ->orWhereNull('kd_entitas');
                //                         })
                //                         ->where(function($query) use ($tanggal_penggajian) {
                //                             $query->whereNull('tanggal_penonaktifan')
                //                                 ->orWhere(function($query2) use ($tanggal_penggajian) {
                //                                     $bulan = date('m', strtotime($tanggal_penggajian));
                //                                     $tahun = date('Y', strtotime($tanggal_penggajian));
                //                                     $query2->where('tanggal_penonaktifan', '>=', $tanggal_penggajian)
                //                                         ->orWhereMonth('tanggal_penonaktifan', $bulan)
                //                                         ->whereYear('tanggal_penonaktifan', $tahun);
                //                                 });
                //                         })
                //                         ->orderByRaw($orderRaw)
                //                         ->get();
                $nama_cabang = "Pusat";
            }
        }

        $result = [];
        if ($request->has('kd_entitas') || auth()->user()->hasRole('cabang')) {
            foreach ($karyawan as $key => $value) {
                $data = CheckHitungPPH::newCheckPPH58($tanggal, $bulan, $tahun, $value);
                $data['tanggal_penonaktifan'] = $value->tanggal_penonaktifan;
                // Pengali DB lama
                $kode_ptkp = $data['old']['pph']->ptkp->kode;
                $penghasilanBrutoDb = $data['old']['pph']->penghasilanBruto;
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
                $data['old']['pph']->lapisan = $lapisanPenghasilanBrutoDB;
                // Pengali DB baru
                $kode_ptkp = $data['new']['pph']->ptkp->kode;
                $penghasilanBrutoDb = $data['new']['pph']->penghasilanBruto;
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
                $data['new']['pph']->lapisan = $lapisanPenghasilanBrutoDB;
                array_push($result, $data);
            }
        }
        // return $result;
        return view('cek-pph-new-25', compact('cabang', 'result', 'nama_cabang', 'bulan', 'tahun'));
    }

    public function update(Request $request) {
        DB::beginTransaction();
        try {
            $bulan = (int) $request->get('bulan');
            $tahun = $request->get('tahun');
            $nipArr = $request->get('nip');
            $terutangArr = $request->get('terutang');
            $now = now();
            foreach ($nipArr as $key => $value) {
                $nominal = (int) $terutangArr[$key];
                $current = DB::table('pph_yang_dilunasi')
                ->where('nip', $value)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

                $current_terutang = $current->terutang;
                $old_selisih = 0;
                $new_terutang = 0;
                $new_selisih = 0;
                $cek_selisih = 0;


                $old_terutang = $current_terutang;
                $new_terutang += $nominal += $current_terutang;
                $new_selisih += $nominal - $old_terutang;
                $cek_selisih += $new_selisih + $old_terutang;

                if ($cek_selisih + $new_terutang != 0) {
                    if ($nominal != 0 ) {
                        if ($current) {
                            DB::table('pph_yang_dilunasi')
                                ->where('nip', $value)
                                ->where('bulan', $bulan)
                                ->where('tahun', $tahun)
                                ->update([
                                    'terutang' => $new_terutang,
                                    'updated_at' => $now
                                ]);
                        }
                    }
                }
            }

            DB::commit();
            Alert::success('Berhasil memperbarui data terutang');
            return redirect()->route('cek-pph.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error($e->getMessage());
            return back();
        }
    }
}
