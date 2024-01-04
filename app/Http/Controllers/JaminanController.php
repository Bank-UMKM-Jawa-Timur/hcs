<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JaminanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getJamsostek(Request $request)
    {
        return view('jaminan.index', [
            'status' => null
        ]);
    }

    public function postJamsostek(Request $request)
    {
        $kantor = $request->kantor;
        $tipe = $request->tipe;
        $karyawan = DB::table('mst_karyawan')
            ->get();

        if ($request->kategori == 1) {
            $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
            $cbg = array();
            foreach ($cabang as $item) {
                array_push($cbg, $item->kd_cabang);
            }

            $data_pusat = DB::table('tunjangan_karyawan')
                ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->whereNotIn('kd_entitas', $cbg)
                ->sum('nominal');

            $data_cabang = DB::table('tunjangan_karyawan')
                ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->whereIn('kd_entitas', $cbg)
                ->selectRaw('kd_entitas, sum(nominal) as nominal')
                ->groupBy('mst_karyawan.kd_entitas')
                ->get();
            // dd($data_cabang);
            return view('jaminan.index', [
                'status' => 1,
                'data_pusat' => $data_pusat,
                'data_cabang' => $data_cabang
            ]);
        }

        if ($kantor == 'Pusat') {
            $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
            $cbg = array();
            foreach ($cabang as $item) {
                array_push($cbg, $item->kd_cabang);
            }
            // dd($cbg);
            $karyawan = DB::table('mst_karyawan')
                ->whereNotIn('kd_entitas', $cbg)
                ->orWhere('kd_entitas', null)
                ->get();

            $total_gaji = array();
            foreach ($karyawan as $i) {
                $data_gaji = DB::table('tunjangan_karyawan')
                    ->join('mst_tunjangan', 'tunjangan_karyawan.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->where('nip', $i->nip)
                    ->where('mst_tunjangan.status', 1)
                    ->sum('tunjangan_karyawan.nominal');
                // dd($i->nama_karyawan. ' '.$data_gaji.' '.  $i->gj_pokok);
                array_push($total_gaji, ($data_gaji + $i->gj_pokok));
            }
        } else {
            $cabang = $request->get('cabang');
            $karyawan = DB::table('mst_karyawan')
                ->where('kd_entitas', $cabang)
                ->get();

            $total_gaji = array();
            foreach ($karyawan as $i) {
                $data_gaji = DB::table('tunjangan_karyawan')
                    ->join('mst_tunjangan', 'tunjangan_karyawan.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->where('nip', $i->nip)
                    ->where('mst_tunjangan.status', 1)
                    ->sum('tunjangan_karyawan.nominal');
                // dd($i->nama_karyawan. ' '.$data_gaji.' '.  $i->gj_pokok);
                array_push($total_gaji, ($data_gaji + $i->gj_pokok));
            }
        }
        // dd($karyawan);
        $jp1 = array();
        $jp2 = array();

        foreach ($total_gaji as $item) {
            $perhitungan_jp1 = ($item > 9077600) ? 9077600 * 0.001 : $item * 0.001;
            $perhitungan_jp2 = ($item > 9077600) ? 9077600 * 0.002 : $item * 0.002;
            array_push($jp1, $perhitungan_jp1);
            array_push($jp2, $perhitungan_jp2);
        }

        return view('jaminan.index', [
            'status' => 2,
            'karyawan' => $karyawan,
            'jp1' => $jp1,
            'jp2' => $jp2
        ]);
    }

    public function index()
    {
        if (!auth()->user()->can('laporan - laporan jamsostek')) {
            return view('roles.forbidden');
        }
        return view('jaminan.index', [
            'karyawan' => null,
            'status' => null
        ]);
    }

    public function filter(Request $request)
    {
        if (!auth()->user()->can('laporan - laporan jamsostek')) {
            return view('roles.forbidden');
        }
        $kantor = $request->kantor;
        $tipe = $request->tipe;
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $karyawan = DB::table('mst_karyawan')->get();

        // If Kategori yang dipilih keseluruhan
        if ($request->kategori == 1) {
            $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
            $cbg = array();
            foreach ($cabang as $item) {
                array_push($cbg, $item->kd_cabang);
            }
            // Get Data untuk kantor pusat
            $karyawan_pusat = DB::table('mst_karyawan')
                ->whereNotIn('kd_entitas', $cbg)
                ->orWhere('kd_entitas', null)
                ->get();

            $jp1_pusat = array();
            $jp2_pusat = array();
            $total_gaji_pusat = array();
            $jkk_pusat = array();

            // Cek Data di table gaji perbulan
            $cek_data = DB::table('gaji_per_bulan')
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->count('*');
            // Jika Data tidak tersedia di table gaji perbulan
            if ($cek_data == 0) {
                foreach ($karyawan_pusat as $i) {
                    if ($i->status_karyawan != 'Nonaktif') {
                        $data_gaji = DB::table('mst_karyawan')
                            ->where('nip', $i->nip)
                            ->select('gj_pokok', 'gj_penyesuaian')
                            ->first();
                        $total_gj = $data_gaji->gj_pokok + $data_gaji->gj_penyesuaian;
                        for ($j = 0; $j <= 8; $j++) {
                            $tj = DB::table('tunjangan_karyawan')
                                ->where('nip', $i->nip)
                                ->where('id_tunjangan', $j)
                                ->first('nominal');
                            $total_gj += ($tj != null) ? $tj->nominal : 0;
                        }
                        array_push($total_gaji_pusat, $total_gj);
                    }
                }
            } else {
                foreach ($karyawan_pusat as $i) {
                    if ($i->status_karyawan != 'Nonaktif') {
                        $data_gaji = DB::table('gaji_per_bulan')
                            ->where('nip', $i->nip)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->first();
                        array_push($total_gaji_pusat, ($data_gaji != null) ? ($data_gaji->gj_pokok + $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan + $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan + $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan + $data_gaji->tj_multilevel) : 0);
                    }
                }
            }

            // dd($total_gaji_pusat);
            foreach ($total_gaji_pusat as $i) {
                array_push($jkk_pusat, $i * 0.0024);
                array_push($jp1_pusat, (($i > 9077600) ? round(9077600 * 0.01) : round($i * 0.01)));
                array_push($jp2_pusat, (($i > 9077600) ? round(9077600 * 0.02) : round($i * 0.02)));
            }

            // dd(array_sum($jp1_pusat));

            $data_pusat = DB::table('tunjangan_karyawan')
                ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->whereNotIn('kd_entitas', $cbg)
                ->get();

            // Get Data keseluruhan cabang
            $data_cabang = DB::table('tunjangan_karyawan')
                            ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                            ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                            ->where('mst_tunjangan.status', 1)
                            ->whereIn('kd_entitas', $cbg)
                            ->selectRaw('kd_entitas, sum(nominal) as nominal')
                            ->groupBy('mst_karyawan.kd_entitas')
                            ->get();

            foreach ($data_cabang as $key => $item) {
                $item->nama_cabang = DB::table('mst_cabang')
                                ->where('kd_cabang', $item->kd_entitas)
                                ->first();

                $karyawan = DB::table('mst_karyawan')
                                ->where('kd_entitas', $item->kd_entitas)
                                ->get();

                $jp1_cabang = array();
                $jp2_cabang = array();
                $total_gaji_cabang = array();

                if($cek_data == 0){
                    foreach($karyawan as $i){
                        if($i->status_karyawan != 'Nonaktif'){
                            $data_gaji = DB::table('mst_karyawan')
                                            ->where('nip', $i->nip)
                                            ->select('gj_pokok', 'gj_penyesuaian')
                                            ->first();
                            $total_gj = $data_gaji->gj_pokok + $data_gaji->gj_penyesuaian;
                            for($j = 0; $j <= 8; $j++){
                                $tj = DB::table('tunjangan_karyawan')
                                        ->where('nip', $i->nip)
                                        ->where('id_tunjangan', $j)
                                        ->first('nominal');
                                $total_gj += ($tj != null) ? $tj->nominal : 0;
                            }
                            array_push($total_gaji_cabang, $total_gj);
                        }
                    }
                } else{
                    foreach($karyawan as $i){
                        if ($i->status_karyawan != 'Nonaktif') {
                            $data_gaji = DB::table('gaji_per_bulan')
                                            ->where('nip', $i->nip)
                                            ->where('bulan', $bulan)
                                            ->where('tahun', $tahun)
                                            ->first();
                            $total_gaji = ($data_gaji != null) ? ($data_gaji->gj_pokok +
                                            $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan +
                                            $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan +
                                            $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan +
                                            $data_gaji->tj_multilevel) : 0;
                            array_push($total_gaji_cabang, $total_gaji);
                        }
                    }
                }
                foreach($total_gaji_cabang as $i){
                    array_push($jp1_cabang, ((($i > 9077600) ? round(9077600 * 0.01) : round($i * 0.01))));
                    array_push($jp2_cabang, ((($i > 9077600) ? round(9077600 * 0.02) : round($i * 0.02))));
                }

                $item->karyawan = $karyawan;
                $item->jp1_cabang = $jp1_cabang;
                $item->jp2_cabang = $jp2_cabang;
                $item->total_gaji_cabang = $total_gaji_cabang;
            }

            return view('jaminan.index', [
                'status' => 1,
                'jp1_pusat' => $jp1_pusat,
                'jp2_pusat' => $jp2_pusat,
                'jkk_pusat' => $jkk_pusat,
                'total_gaji_pusat' => array_sum($total_gaji_pusat),
                'data_pusat' => $data_pusat,
                'count_pusat' => count($karyawan_pusat),
                'data_cabang' => $data_cabang,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'request' => $request,
            ]);
        }

        // Get data per kantor/cabang
        // if kantor = pusat
        $total_gaji = array();
        $cab = null;
        $cek_data = DB::table('gaji_per_bulan')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->count('*');
        if ($kantor == 'Pusat') {
            $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
            $cbg = array();
            foreach ($cabang as $item) {
                array_push($cbg, $item->kd_cabang);
            }
            // dd($cbg);
            $karyawan = DB::table('mst_karyawan')
                ->whereNotIn('kd_entitas', $cbg)
                ->orWhere('kd_entitas', null)
                ->get();

            if ($cek_data == 0) {
                foreach ($karyawan as $i) {
                    if ($i->status_karyawan != 'Nonaktif') {
                        $data_gaji = DB::table('mst_karyawan')
                            ->where('nip', $i->nip)
                            ->select('gj_pokok', 'gj_penyesuaian')
                            ->first();
                        $total_gj = $data_gaji->gj_pokok + $data_gaji->gj_penyesuaian;
                        for ($j = 0; $j <= 8; $j++) {
                            $tj = DB::table('tunjangan_karyawan')
                                ->where('nip', $i->nip)
                                ->where('id_tunjangan', $j)
                                ->first('nominal');
                            $total_gj += ($tj != null) ? $tj->nominal : 0;
                        }
                        array_push($total_gaji, $total_gj);
                    }
                }
            } else {
                foreach ($karyawan as $i) {
                    if ($i->status_karyawan != 'Nonaktif') {
                        $data_gaji = DB::table('gaji_per_bulan')
                            ->where('nip', $i->nip)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->first();
                        array_push($total_gaji, ($data_gaji != null) ? ($data_gaji->gj_pokok + $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan + $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan + $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan + $data_gaji->tj_multilevel) : 0);
                    }
                }
            }
        } elseif ($kantor == 'Cabang') {
            $tahun = $request->tahun;
            $bulan = $request->bulan;
            $cabang = $request->get('cabang');
            $karyawan = DB::table('mst_karyawan')
                ->where('kd_entitas', $cabang)
                ->get();
            $cab = DB::table('mst_cabang')
                ->where('kd_cabang', $cabang)
                ->first();
            if ($cek_data == 0) {
                foreach ($karyawan as $i) {
                    if ($i->status_karyawan != 'Nonaktif') {
                        $data_gaji = DB::table('mst_karyawan')
                            ->where('nip', $i->nip)
                            ->select('gj_pokok', 'gj_penyesuaian')
                            ->first();
                        $total_gj = $data_gaji->gj_pokok + $data_gaji->gj_penyesuaian;
                        for ($j = 0; $j <= 8; $j++) {
                            $tj = DB::table('tunjangan_karyawan')
                                ->where('nip', $i->nip)
                                ->where('id_tunjangan', $j)
                                ->first('nominal');
                            $total_gj += ($tj != null) ? $tj->nominal : 0;
                        }
                        array_push($total_gaji, $total_gj);
                    }
                }
            } else {
                foreach ($karyawan as $i) {
                    if ($i->status_karyawan != 'Nonaktif') {
                        $data_gaji = DB::table('gaji_per_bulan')
                            ->where('nip', $i->nip)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->first();
                        array_push($total_gaji, ($data_gaji != null) ? ($data_gaji->gj_pokok + $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan + $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan + $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan + $data_gaji->tj_multilevel) : 0);
                    }
                }
            }
        }

        $jkk = array();
        $jht = array();
        $jkm = array();
        $jp1 = array();
        $jp2 = array();

        foreach ($total_gaji as $item) {
            $perhitungan_jkk = 0.0024 * $item;
            $perhitungan_jht = 0.057 * $item;
            $perhitungan_jkm = 0.003 * $item;
            $perhitungan_jp1 = ($item > 9077600) ? round(9077600 * 0.01) : round($item * 0.01);
            $perhitungan_jp2 = ($item > 9077600) ? round(9077600 * 0.02) : round($item * 0.02);
            array_push($jp1, $perhitungan_jp1);
            array_push($jp2, $perhitungan_jp2);
            array_push($jkk, $perhitungan_jkk);
            array_push($jht, $perhitungan_jht);
            array_push($jkm, $perhitungan_jkm);
        }

        return view('jaminan.index', [
            'status' => 2,
            'kantor' => $kantor,
            'cab' => $cab,
            'karyawan' => $karyawan,
            'jkk' => $jkk,
            'jht' => $jht,
            'jkm' => $jkm,
            'jp1' => $jp1,
            'jp2' => $jp2,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'request' => $request,
        ]);
    }

    public function dppIndex()
    {
        if (!auth()->user()->can('laporan - laporan dpp')) {
            return view('roles.forbidden');
        }
        return view('jaminan.dpp_index');
    }

    public function getDPP(Request $request)
    {
        if (!auth()->user()->can('laporan - laporan dpp')) {
            return view('roles.forbidden');
        }
        $kantor = $request->kantor;
        $kategori = $request->kategori;
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        // If yang dipilih kategori keseluruhan
        if ($kategori == 1) {
            // Cek Data Di Table Gaji Perbulan
            $cek_data = DB::table('gaji_per_bulan')
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->count('*');
            $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
            $cbg = array();
            foreach ($cabang as $item) {
                array_push($cbg, $item->kd_cabang);
            }

            $karyawan_pusat = DB::table('mst_karyawan')
                ->whereNotIn('kd_entitas', $cbg)
                ->orWhere('kd_entitas', null)
                ->where('status_karyawan', 'Tetap')
                ->get();

            $total_tunjangan_keluarga = array();
            $total_tunjangan_kesejahteraan = array();
            $total_gj_pusat = array();
            $s = array();
            // Jika Data Belum Tersedia Di Table Gaji Perbulan
            if ($cek_data == 0) {
                foreach ($karyawan_pusat as $i) {
                    if ($i->status_karyawan == 'Tetap') {
                        $data_gaji = DB::table('mst_karyawan')
                            ->where('nip', $i->nip)
                            ->select('gj_pokok', 'gj_penyesuaian')
                            ->first();
                        $data_tj_keluarga = DB::table('tunjangan_karyawan')
                            ->where('nip', $i->nip)
                            ->where('id_tunjangan', 1)
                            ->first();
                        $data_tj_kesejahteraan = DB::table('tunjangan_karyawan')
                            ->where('nip', $i->nip)
                            ->where('id_tunjangan', 8)
                            ->first();

                        array_push($total_gj_pusat, ($data_gaji != null) ? $data_gaji->gj_pokok : 0);
                        array_push($total_tunjangan_keluarga, ($data_tj_keluarga != null) ? $data_tj_keluarga->nominal : 0);
                        array_push($total_tunjangan_kesejahteraan, ($data_tj_kesejahteraan != null) ? $data_tj_kesejahteraan->nominal : 0);
                    }
                }
                $dpp_pusat = round((array_sum($total_gj_pusat) + array_sum($total_tunjangan_keluarga) + (array_sum($total_tunjangan_kesejahteraan) * 0.5)) * 0.13);

                $data_pusat = DB::table('tunjangan_karyawan')
                    ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                    ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                    ->where('mst_tunjangan.status', 1)
                    ->whereNotIn('kd_entitas', $cbg)
                    ->get();

                // Get Data keseluruhan cabang
                $data_cabang = DB::table('tunjangan_karyawan')
                    ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                    ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                    ->where('mst_tunjangan.status', 1)
                    ->whereIn('kd_entitas', $cbg)
                    ->selectRaw('kd_entitas, sum(nominal) as nominal')
                    ->groupBy('mst_karyawan.kd_entitas')
                    ->get();

                return view('jaminan.dpp_index', [
                    'status' => 1,
                    'dpp_pusat' => $dpp_pusat,
                    'data_pusat' => $data_pusat,
                    'data_cabang' => $data_cabang,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'request' => $request,
                ]);
            }

            foreach ($karyawan_pusat as $i) {
                if ($i->status_karyawan == 'Tetap') {
                    $data_gaji = DB::table('gaji_per_bulan')
                        ->where('nip', $i->nip)
                        ->where('bulan', $bulan)
                        ->where('tahun', $tahun)
                        ->first();

                    array_push($total_tunjangan_keluarga, ($data_gaji != null) ? $data_gaji->tj_keluarga : 0);
                    array_push($total_tunjangan_kesejahteraan, ($data_gaji != null) ? $data_gaji->tj_kesejahteraan : 0);
                    array_push($total_gj_pusat, ($data_gaji != null) ? $data_gaji->gj_pokok : 0);
                    array_push($s, $i->status_karyawan);
                }
            }
            $dpp_pusat = round((array_sum($total_gj_pusat) + array_sum($total_tunjangan_keluarga) + (array_sum($total_tunjangan_kesejahteraan) * 0.5)) * 0.13);

            // dd($gj_pusat);
            $data_pusat = DB::table('tunjangan_karyawan')
                ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->whereNotIn('kd_entitas', $cbg)
                ->get();

            // Get Data keseluruhan cabang
            $data_cabang = DB::table('tunjangan_karyawan')
                ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->whereIn('kd_entitas', $cbg)
                ->selectRaw('kd_entitas, sum(nominal) as nominal')
                ->groupBy('mst_karyawan.kd_entitas')
                ->get();

            return view('jaminan.dpp_index', [
                'status' => 1,
                'dpp_pusat' => $dpp_pusat,
                'data_pusat' => $data_pusat,
                'data_cabang' => $data_cabang,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'request' => $request,
            ]);
        }

        // Get data per kantor/cabang
        $cab = null;
        $kantor = $request->kantor;
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $dpp = array();

        $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
        $cbg = array();
        foreach ($cabang as $item) {
            array_push($cbg, $item->kd_cabang);
        }

        // if kantor = pusat
        if ($kantor == 'Pusat') {
            $karyawan = DB::table('mst_karyawan')
                ->whereNotIn('kd_entitas', $cbg)
                ->orWhere('kd_entitas', null)
                ->where('status_karyawan', 'Tetap')
                ->get();

            $cek_data = DB::table('gaji_per_bulan')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->count('*');
            if ($cek_data == 0) {
                foreach ($karyawan as $i) {
                    if ($i->status_karyawan == 'Tetap') {
                        $data_gaji = DB::table('tunjangan_karyawan')
                            ->where('id_tunjangan', 15)
                            ->where('nip', $i->nip)
                            ->first();
                        array_push($dpp, ($data_gaji != null) ? $data_gaji->nominal : 0);
                    }
                }
            } else {
                foreach ($karyawan as $i) {
                    if ($i->status_karyawan == 'Tetap') {
                        $data_gaji = DB::table('gaji_per_bulan')
                            ->where('nip', $i->nip)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->first();
                        array_push($dpp, ($data_gaji != null) ? $data_gaji->dpp : 0);
                    }
                }
            }
        } else {
            $cabang = $request->get('cabang');
            $karyawan = DB::table('mst_karyawan')
                ->where('kd_entitas', $cabang)
                ->where('status_karyawan', 'Tetap')
                ->get();
            $cab = DB::table('mst_cabang')
                ->where('kd_cabang', $cabang)
                ->first();

            $cek_data = DB::table('gaji_per_bulan')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->count('*');
            if ($cek_data == 0) {
                foreach ($karyawan as $i) {
                    if ($i->status_karyawan == 'Tetap') {
                        $data_gaji = DB::table('tunjangan_karyawan')
                            ->where('id_tunjangan', 15)
                            ->where('nip', $i->nip)
                            ->first();
                        array_push($dpp, ($data_gaji != null) ? $data_gaji->nominal : 0);
                    }
                }
            } else {
                foreach ($karyawan as $i) {
                    if ($i->status_karyawan == 'Tetap') {
                        $data_gaji = DB::table('gaji_per_bulan')
                            ->where('nip', $i->nip)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->first();
                        array_push($dpp, ($data_gaji != null) ? $data_gaji->dpp : 0);
                    }
                }
            }
        }

        return view('jaminan.dpp_index', [
            'status' => 2,
            'kantor' => $kantor,
            'cab' => $cab,
            'karyawan' => $karyawan,
            'dpp' => $dpp,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'request' => $request,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
