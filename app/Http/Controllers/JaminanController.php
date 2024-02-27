<?php

namespace App\Http\Controllers;

use App\Helpers\GajiComponent;
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
    private String $orderRaw;
    public function __construct()
    {
        $this->orderRaw = "
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
    }

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
            $data = DB::table('gaji_per_bulan')
                ->select(
                    DB::raw('COUNT(gaji_per_bulan.id) as total_karyawan'),
                    'batch_gaji_per_bulan.kd_entitas',
                    DB::raw('gaji_per_bulan.dpp'),
                    DB::raw('SUM(gaji_per_bulan.bpjs_tk) as jp1'),
                    DB::raw('SUM(gaji_per_bulan.bpjs_tk_two) as jp2'),
                    DB::raw('SUM(gaji_per_bulan.jkk) as perhitungan_jkk'),
                    DB::raw('SUM(gaji_per_bulan.jht) as perhitungan_jht'),
                    DB::raw('SUM(gaji_per_bulan.jkm) as perhitungan_jkm'),
                    DB::raw('SUM(gaji_per_bulan.kesehatan)'),
                    DB::raw('SUM(gaji_per_bulan.jp)'),
                    DB::raw('SUM(gaji_per_bulan.penambah_bruto_jamsostek)')
                )
                ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', '=', 'gaji_per_bulan.batch_id')
                ->where('gaji_per_bulan.tahun', $tahun)
                ->where('gaji_per_bulan.bulan', $bulan)
                ->whereIn('batch_gaji_per_bulan.kd_entitas', $cbg)
                ->whereNull('batch_gaji_per_bulan.deleted_at')
                ->orderBy('batch_gaji_per_bulan.kd_entitas')
                ->groupBy('batch_gaji_per_bulan.kd_entitas')
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
            // if ($cek_data == 0) {
            //     foreach ($karyawan_pusat as $i) {
            //         if ($i->status_karyawan != 'Nonaktif') {
            //             $data_gaji = DB::table('mst_karyawan')
            //                 ->where('nip', $i->nip)
            //                 ->select('gj_pokok', 'gj_penyesuaian')
            //                 ->first();
            //             $total_gj = $data_gaji->gj_pokok + $data_gaji->gj_penyesuaian;
            //             for ($j = 0; $j <= 8; $j++) {
            //                 $tj = DB::table('tunjangan_karyawan')
            //                     ->where('nip', $i->nip)
            //                     ->where('id_tunjangan', $j)
            //                     ->first('nominal');
            //                 $total_gj += ($tj != null) ? $tj->nominal : 0;
            //             }
            //             array_push($total_gaji_pusat, $total_gj);
            //         }
            //     }
            // } else {
            //     foreach ($karyawan_pusat as $i) {
            //         if ($i->status_karyawan != 'Nonaktif') {
            //             $data_gaji = DB::table('gaji_per_bulan')
            //                 ->where('nip', $i->nip)
            //                 ->where('bulan', $bulan)
            //                 ->where('tahun', $tahun)
            //                 ->first();
            //             array_push($total_gaji_pusat, ($data_gaji != null) ? ($data_gaji->gj_pokok + $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan + $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan + $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan + $data_gaji->tj_multilevel) : 0);
            //         }
            //     }
            // }

            // dd($total_gaji_pusat);
            // foreach ($total_gaji_pusat as $i) {
            //     array_push($jkk_pusat, $i * 0.0024);
            //     array_push($jp1_pusat, (($i > 9077600) ? round(9077600 * 0.01) : round($i * 0.01)));
            //     array_push($jp2_pusat, (($i > 9077600) ? round(9077600 * 0.02) : round($i * 0.02)));
            // }

            // dd(array_sum($jp1_pusat));

            // $data_pusat = DB::table('tunjangan_karyawan')
            //     ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
            //     ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
            //     ->where('mst_tunjangan.status', 1)
            //     ->whereNotIn('kd_entitas', $cbg)
            //     ->get();

            // Get Data keseluruhan cabang
            // $data_cabang = DB::table('tunjangan_karyawan')
            //                 ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
            //                 ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
            //                 ->where('mst_tunjangan.status', 1)
            //                 ->whereIn('kd_entitas', $cbg)
            //                 ->selectRaw('kd_entitas, sum(nominal) as nominal')
            //                 ->groupBy('mst_karyawan.kd_entitas')
            //                 ->get();

            // foreach ($data_cabang as $key => $item) {
            //     $item->nama_cabang = DB::table('mst_cabang')
            //                     ->where('kd_cabang', $item->kd_entitas)
            //                     ->first();

            //     $karyawan = DB::table('mst_karyawan')
            //                     ->where('kd_entitas', $item->kd_entitas)
            //                     ->get();

            //     $jp1_cabang = array();
            //     $jp2_cabang = array();
            //     $total_gaji_cabang = array();

            //     if($cek_data == 0){
            //         foreach($karyawan as $i){
            //             if($i->status_karyawan != 'Nonaktif'){
            //                 $data_gaji = DB::table('mst_karyawan')
            //                                 ->where('nip', $i->nip)
            //                                 ->select('gj_pokok', 'gj_penyesuaian')
            //                                 ->first();
            //                 $total_gj = $data_gaji->gj_pokok + $data_gaji->gj_penyesuaian;
            //                 for($j = 0; $j <= 8; $j++){
            //                     $tj = DB::table('tunjangan_karyawan')
            //                             ->where('nip', $i->nip)
            //                             ->where('id_tunjangan', $j)
            //                             ->first('nominal');
            //                     $total_gj += ($tj != null) ? $tj->nominal : 0;
            //                 }
            //                 array_push($total_gaji_cabang, $total_gj);
            //             }
            //         }
            //     } else{
            //         foreach($karyawan as $i){
            //             if ($i->status_karyawan != 'Nonaktif') {
            //                 $data_gaji = DB::table('gaji_per_bulan')
            //                                 ->where('nip', $i->nip)
            //                                 ->where('bulan', $bulan)
            //                                 ->where('tahun', $tahun)
            //                                 ->first();
            //                 $total_gaji = ($data_gaji != null) ? ($data_gaji->gj_pokok +
            //                                 $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan +
            //                                 $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan +
            //                                 $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan +
            //                                 $data_gaji->tj_multilevel) : 0;
            //                 array_push($total_gaji_cabang, $total_gaji);
            //             }
            //         }
            //     }
            //     foreach($total_gaji_cabang as $i){
            //         array_push($jp1_cabang, ((($i > 9077600) ? round(9077600 * 0.01) : round($i * 0.01))));
            //         array_push($jp2_cabang, ((($i > 9077600) ? round(9077600 * 0.02) : round($i * 0.02))));
            //     }

            //     $item->karyawan = $karyawan;
            //     $item->jp1_cabang = $jp1_cabang;
            //     $item->jp2_cabang = $jp2_cabang;
            //     $item->total_gaji_cabang = $total_gaji_cabang;
            // }

            return view('jaminan.index', [
                'status' => 1,
                'jp1_pusat' => $jp1_pusat,
                'jp2_pusat' => $jp2_pusat,
                'jkk_pusat' => $jkk_pusat,
                'total_gaji_pusat' => array_sum($total_gaji_pusat),
                // 'data_pusat' => $data_pusat,
                'data' => $data,
                'count_pusat' => count($karyawan_pusat),
                // 'data_cabang' => $data_cabang,
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
        $cabang = $request->get('cabang');
        $cab = DB::table('mst_cabang')
            ->where('kd_cabang', $cabang)
            ->first();
        if ($kantor == 'Pusat') {
            // dd($cbg);
            $karyawan = DB::table('gaji_per_bulan')
            ->select(
                'gaji_per_bulan.nip',
                'mst_karyawan.nama_karyawan',
                'gaji_per_bulan.dpp',
                'gaji_per_bulan.bpjs_tk as jp1',
                'gaji_per_bulan.bpjs_tk_two as jp2',
                'gaji_per_bulan.jkk as perhitungan_jkk',
                'gaji_per_bulan.jht as perhitungan_jht',
                'gaji_per_bulan.jkm as perhitungan_jkm',
                'gaji_per_bulan.kesehatan',
                'gaji_per_bulan.jp',
                'gaji_per_bulan.penambah_bruto_jamsostek'
            )
            ->join('batch_gaji_per_bulan', 'gaji_per_bulan.batch_id', 'batch_gaji_per_bulan.id')
            ->join('mst_karyawan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
            ->where('gaji_per_bulan.tahun', $tahun)
            ->where('gaji_per_bulan.bulan', $bulan)
            ->whereRaw("(mst_karyawan.tanggal_penonaktifan IS NULL OR ($bulan = MONTH(mst_karyawan.tanggal_penonaktifan) AND mst_karyawan.is_proses_gaji = 1))")
            ->where('batch_gaji_per_bulan.kd_entitas', '000')
            ->whereNull('batch_gaji_per_bulan.deleted_at')
            ->get();

            // if ($cek_data == 0) {
            //     foreach ($karyawan as $i) {
            //         if ($i->status_karyawan != 'Nonaktif') {
            //             $data_gaji = DB::table('mst_karyawan')
            //                 ->where('nip', $i->nip)
            //                 ->select('gj_pokok', 'gj_penyesuaian')
            //                 ->first();
            //             $total_gj = $data_gaji->gj_pokok + $data_gaji->gj_penyesuaian;
            //             for ($j = 0; $j <= 8; $j++) {
            //                 $tj = DB::table('tunjangan_karyawan')
            //                     ->where('nip', $i->nip)
            //                     ->where('id_tunjangan', $j)
            //                     ->first('nominal');
            //                 $total_gj += ($tj != null) ? $tj->nominal : 0;
            //             }
            //             array_push($total_gaji, $total_gj);
            //         }
            //     }
            // } else {
            //     foreach ($karyawan as $i) {
            //         if ($i->status_karyawan != 'Nonaktif') {
            //             $data_gaji = DB::table('gaji_per_bulan')
            //                 ->where('nip', $i->nip)
            //                 ->where('bulan', $bulan)
            //                 ->where('tahun', $tahun)
            //                 ->first();
            //             array_push($total_gaji, ($data_gaji != null) ? ($data_gaji->gj_pokok + $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan + $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan + $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan + $data_gaji->tj_multilevel) : 0);
            //         }
            //     }
            // }
        } elseif ($kantor == 'Cabang') {
            $tahun = $request->tahun;
            $bulan = $request->bulan;
            $karyawan = DB::table('gaji_per_bulan')
                ->select('gaji_per_bulan.nip',
                        'mst_karyawan.nama_karyawan',
                        'gaji_per_bulan.dpp',
                        'gaji_per_bulan.bpjs_tk as jp1',
                        'gaji_per_bulan.bpjs_tk_two as jp2',
                        'gaji_per_bulan.jkk as perhitungan_jkk',
                        'gaji_per_bulan.jht as perhitungan_jht',
                        'gaji_per_bulan.jkm as perhitungan_jkm',
                        'gaji_per_bulan.kesehatan',
                        'gaji_per_bulan.jp',
                        'gaji_per_bulan.penambah_bruto_jamsostek'
                        )
                ->join('batch_gaji_per_bulan', 'gaji_per_bulan.batch_id', 'batch_gaji_per_bulan.id')
                ->join('mst_karyawan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                ->where('gaji_per_bulan.tahun', $tahun)
                ->where('gaji_per_bulan.bulan', $bulan)
                ->whereRaw("(mst_karyawan.tanggal_penonaktifan IS NULL OR ($bulan = MONTH(mst_karyawan.tanggal_penonaktifan) AND mst_karyawan.is_proses_gaji = 1))")
                ->where('batch_gaji_per_bulan.kd_entitas', $cab->kd_cabang)
                ->whereNull('batch_gaji_per_bulan.deleted_at')
                ->get();
            // if ($cek_data == 0) {
            //     foreach ($karyawan as $i) {
            //         if ($i->status_karyawan != 'Nonaktif') {
            //             $data_gaji = DB::table('mst_karyawan')
            //                 ->where('nip', $i->nip)
            //                 ->select('gj_pokok', 'gj_penyesuaian')
            //                 ->first();
            //             $total_gj = $data_gaji->gj_pokok + $data_gaji->gj_penyesuaian;
            //             for ($j = 0; $j <= 8; $j++) {
            //                 $tj = DB::table('tunjangan_karyawan')
            //                     ->where('nip', $i->nip)
            //                     ->where('id_tunjangan', $j)
            //                     ->first('nominal');
            //                 $total_gj += ($tj != null) ? $tj->nominal : 0;
            //             }
            //             array_push($total_gaji, $total_gj);
            //         }
            //     }
            // } else {
            //     foreach ($karyawan as $i) {
            //         $gaji_component = new GajiComponent($i->kd_entitas);
            //         if ($i->status_karyawan != 'Nonaktif') {
            //             $data_gaji = DB::table('gaji_per_bulan')
            //                 ->join('batch_gaji_per_bulan', 'gaji_per_bulan.batch_id', 'batch_gaji_per_bulan.id')
            //                 ->where('gaji_per_bulan.nip', $i->nip)
            //                 ->where('gaji_per_bulan.bulan', $bulan)
            //                 ->where('gaji_per_bulan.tahun', $tahun)
            //                 ->whereNull('batch_gaji_per_bulan.deleted_at')
            //                 ->first();
            //             if ($data_gaji) {
            //                 $i->total_gaji = $data_gaji->gj_pokok + $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan + $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan + $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan + $data_gaji->tj_multilevel;
            //             } else {
            //                 $i->total_gaji = 0;
            //             }
            //             $i->perhitungan_jkk = 0.0024 * $i->total_gaji;
            //             $i->perhitungan_jht = 0.057 * $i->total_gaji;
            //             $i->perhitungan_jkm = 0.003 * $i->total_gaji;
            //             $i->perhitungan_jkm = 0.003 * $i->total_gaji;
            //             $i->jp1 = $gaji_component->getBPJSTK($i->kpj, $i->total_gaji, $bulan, false, false);
            //             $i->jp2 = $gaji_component->getBPJSTK($i->kpj, $i->total_gaji, $bulan, false, true);
            //             // array_push($total_gaji, ($data_gaji != null) ? ($data_gaji->gj_pokok + $data_gaji->gj_penyesuaian + $data_gaji->tj_keluarga + $data_gaji->tj_jabatan + $data_gaji->tj_telepon + $data_gaji->tj_teller + $data_gaji->tj_perumahan + $data_gaji->tj_kemahalan + $data_gaji->tj_pelaksana + $data_gaji->tj_kesejahteraan + $data_gaji->tj_multilevel) : 0);
            //         }
            //     }
            // }
        }

        // $jkk = array();
        // $jht = array();
        // $jkm = array();
        // $jp1 = array();
        // $jp2 = array();
        // foreach ($total_gaji as $item) {
        //     $perhitungan_jkk = 0.0024 * $item;
        //     $perhitungan_jht = 0.057 * $item;
        //     $perhitungan_jkm = 0.003 * $item;
        //     $perhitungan_jp1 = ($item > 9077600) ? round(9077600 * 0.01) : round($item * 0.01);
        //     $perhitungan_jp2 = ($item > 9077600) ? round(9077600 * 0.02) : round($item * 0.02);
        //     array_push($jp1, $perhitungan_jp1);
        //     array_push($jp2, $perhitungan_jp2);
        //     array_push($jkk, $perhitungan_jkk);
        //     array_push($jht, $perhitungan_jht);
        //     array_push($jkm, $perhitungan_jkm);
        // }
        // return $karyawan;s

        return view('jaminan.index', [
            'status' => 2,
            'kantor' => $kantor,
            'cab' => $cab,
            'karyawan' => $karyawan,
            // 'jkk' => $jkk,
            // 'jht' => $jht,
            // 'jkm' => $jkm,
            // 'jp1' => $jp1,
            // 'jp2' => $jp2,
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
        return view('jaminan.dpp_index', ['status' => null]);
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
                ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                ->where('gaji_per_bulan.tahun', $tahun)
                ->where('gaji_per_bulan.bulan', $bulan)
                ->whereNull('batch_gaji_per_bulan.deleted_at')
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
                // foreach ($karyawan_pusat as $i) {
                //     if ($i->status_karyawan == 'Tetap') {

                //         $data_gaji = DB::table('mst_karyawan')
                //             ->where('nip', $i->nip)
                //             ->select('gj_pokok', 'gj_penyesuaian')
                //             ->first();
                //         $data_tj_keluarga = DB::table('tunjangan_karyawan')
                //             ->where('nip', $i->nip)
                //             ->where('id_tunjangan', 1)
                //             ->first();
                //         $data_tj_kesejahteraan = DB::table('tunjangan_karyawan')
                //             ->where('nip', $i->nip)
                //             ->where('id_tunjangan', 8)
                //             ->first();

                //         array_push($total_gj_pusat, ($data_gaji != null) ? $data_gaji->gj_pokok : 0);
                //         array_push($total_tunjangan_keluarga, ($data_tj_keluarga != null) ? $data_tj_keluarga->nominal : 0);
                //         array_push($total_tunjangan_kesejahteraan, ($data_tj_kesejahteraan != null) ? $data_tj_kesejahteraan->nominal : 0);
                //     }
                // }
                // $dpp_pusat = round((array_sum($total_gj_pusat) + array_sum($total_tunjangan_keluarga) + (array_sum($total_tunjangan_kesejahteraan) * 0.5)) * 0.13);

                // $data_pusat = DB::table('tunjangan_karyawan')
                //     ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                //     ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                //     ->where('mst_tunjangan.status', 1)
                //     ->whereNotIn('kd_entitas', $cbg)
                //     ->get();

                // Get Data keseluruhan cabang
                $data_cabang = DB::table('gaji_per_bulan')
                    ->select(
                        DB::raw('SUM(gaji_per_bulan.dpp) as dpp'),
                        'batch_gaji_per_bulan.kd_entitas'
                    )
                    ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', '=', 'gaji_per_bulan.batch_id')
                    ->where('gaji_per_bulan.tahun', $tahun)
                    ->where('gaji_per_bulan.bulan', $bulan)
                    ->whereIn('batch_gaji_per_bulan.kd_entitas', $cbg)
                    ->whereNull('batch_gaji_per_bulan.deleted_at')
                    ->orderBy('batch_gaji_per_bulan.kd_entitas')
                    ->groupBy('batch_gaji_per_bulan.kd_entitas')
                    ->get();
                // $data_cabang = DB::table('tunjangan_karyawan')
                //     ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                //     ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                //     ->where('mst_tunjangan.status', 1)
                //     ->whereIn('kd_entitas', $cbg)
                //     ->selectRaw('kd_entitas, sum(nominal) as nominal')
                //     ->groupBy('mst_karyawan.kd_entitas')
                //     ->get();
                return view('jaminan.dpp_index', [
                    'status' => 1,
                    // 'dpp_pusat' => $dpp_pusat,
                    // 'data_pusat' => $data_pusat,
                    'data_cabang' => $data_cabang,
                    'tahun' => $request->tahun,
                    'bulan' => $bulan,
                    'request' => $request,
                ]);
            }

            foreach ($karyawan_pusat as $i) {
                if ($i->status_karyawan == 'Tetap') {
                    $data_gaji = DB::table('gaji_per_bulan')
                        ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                        ->where('gaji_per_bulan.tahun', $tahun)
                        ->where('gaji_per_bulan.bulan', $bulan)
                        ->whereNull('batch_gaji_per_bulan.deleted_at')
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
            $data_cabang = DB::table('gaji_per_bulan')
                ->select(
                    DB::raw('SUM(gaji_per_bulan.dpp) as dpp'),
                    'batch_gaji_per_bulan.kd_entitas'
                )
                ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', '=', 'gaji_per_bulan.batch_id')
                ->where('gaji_per_bulan.tahun', $tahun)
                ->where('gaji_per_bulan.bulan', $bulan)
                ->whereIn('batch_gaji_per_bulan.kd_entitas', $cbg)
                ->whereNull('batch_gaji_per_bulan.deleted_at')
                ->orderBy('batch_gaji_per_bulan.kd_entitas')
                ->groupBy('batch_gaji_per_bulan.kd_entitas')
                ->get();
            // $data_cabang = DB::table('tunjangan_karyawan')
            //     ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
            //     ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
            //     ->where('mst_tunjangan.status', 1)
            //     ->whereIn('kd_entitas', $cbg)
            //     ->selectRaw('kd_entitas, sum(nominal) as nominal')
            //     ->groupBy('mst_karyawan.kd_entitas')
            //     ->get();

            return view('jaminan.dpp_index', [
                'status' => 1,
                'dpp_pusat' => $dpp_pusat,
                'data_pusat' => $data_pusat,
                'data_cabang' => $data_cabang,
                'tahun' => $request->tahun,
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
                ->orWhereNull('kd_entitas')
                // ->whereRaw("(tanggal_penonaktifan IS NULL OR ($bulan = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                ->where('status_karyawan', 'Tetap')
                ->get();

            $cek_data = DB::table('gaji_per_bulan')
                ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                ->where('gaji_per_bulan.tahun', $tahun)
                ->where('gaji_per_bulan.bulan', $bulan)
                ->whereNull('batch_gaji_per_bulan.deleted_at')
                ->count('*');
            // if ($cek_data == 0) {
            //     foreach ($karyawan as $i) {
            //         if ($i->status_karyawan == 'Tetap') {
            //             $data_gaji = DB::table('tunjangan_karyawan')
            //                 ->where('id_tunjangan', 15)
            //                 ->where('nip', $i->nip)
            //                 ->first();
            //             array_push($dpp, ($data_gaji != null) ? $data_gaji->nominal : 0);
            //         }
            //     }
            // } else {
                $data_gaji = DB::table('gaji_per_bulan')
                ->select(
                    'gaji_per_bulan.nip',
                    'mst_karyawan.nama_karyawan',
                    'gaji_per_bulan.dpp'
                )
                ->join('mst_karyawan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                ->where('gaji_per_bulan.tahun', $tahun)
                ->whereRaw("(tanggal_penonaktifan IS NULL OR ($bulan = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                ->where('gaji_per_bulan.bulan', $bulan)
                ->where('batch_gaji_per_bulan.kd_entitas', '000')
                ->whereNull('batch_gaji_per_bulan.deleted_at')
                ->orderBy('gaji_per_bulan.nip')
                ->get();
                // foreach ($karyawan as $i) {
                //     if ($i->status_karyawan == 'Tetap') {
                //         $data_gaji = DB::table('gaji_per_bulan')
                //             ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                //             ->where('gaji_per_bulan.tahun', $tahun)
                //             ->where('gaji_per_bulan.bulan', $bulan)
                //             ->whereNull('batch_gaji_per_bulan.deleted_at')
                //             ->where('batch_gaji_per_bulan.kd_entitas', '000')
                //             ->first();
                //         array_push($dpp, ($data_gaji != null) ? $data_gaji->dpp : 0);
                //     }
                // }
            // }
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
                ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                ->where('gaji_per_bulan.bulan', $bulan)
                ->where('gaji_per_bulan.tahun', $tahun)
                ->whereNull('batch_gaji_per_bulan.deleted_at')
                ->count('*');
            // if ($cek_data == 0) {
            //     foreach ($karyawan as $i) {
            //         if ($i->status_karyawan == 'Tetap') {
            //             $data_gaji = DB::table('tunjangan_karyawan')
            //                 ->where('id_tunjangan', 15)
            //                 ->where('nip', $i->nip)
            //                 ->first();
            //             array_push($dpp, ($data_gaji != null) ? $data_gaji->nominal : 0);
            //         }
            //     }
            // } else {
                // foreach ($karyawan as $i) {
                //     if ($i->status_karyawan == 'Tetap') {
                //         $data_gaji = DB::table('gaji_per_bulan')
                //             ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                //             ->where('gaji_per_bulan.tahun', $tahun)
                //             ->where('gaji_per_bulan.bulan', $bulan)
                //             ->where('gaji_per_bulan.nip', $i->nip)
                //             ->whereNull('batch_gaji_per_bulan.deleted_at')
                //             ->first();
                //         array_push($dpp, ($data_gaji != null) ? $data_gaji->dpp : 0);
                //     }
                // }
                $data_gaji = DB::table('gaji_per_bulan')
                    ->select(
                        'gaji_per_bulan.nip',
                        'mst_karyawan.nama_karyawan',
                        'gaji_per_bulan.dpp'
                    )
                    ->join('mst_karyawan', 'gaji_per_bulan.nip', 'mst_karyawan.nip')
                    ->join('batch_gaji_per_bulan', 'batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                    ->where('gaji_per_bulan.tahun', $tahun)
                    ->whereRaw("(tanggal_penonaktifan IS NULL OR ($bulan = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                    ->where('gaji_per_bulan.bulan', $bulan)
                    ->where('batch_gaji_per_bulan.kd_entitas', $cab->kd_cabang)
                    ->whereNull('batch_gaji_per_bulan.deleted_at')
                    ->orderBy('gaji_per_bulan.nip')
                    ->get();
            // }
        }

        return view('jaminan.dpp_index', [
            'status' => 2,
            'kantor' => $kantor,
            'cab' => $cab,
            'data_gaji' => $data_gaji,
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
