<?php

namespace App\Http\Controllers;

use App\Exports\ProsesPayroll;
use App\Exports\ProsesRincianPayroll;
use App\Helpers\GajiComponent;
use App\Helpers\HitungPPH;
use App\Imports\ImportPPH21;
use App\Models\CabangModel;
use App\Models\GajiPerBulanModel;
use App\Models\KaryawanModel;
use App\Models\PPHModel;
use App\Models\TunjanganModel;
use App\Repository\PayrollRepository;
use App\Repository\CetakGajiRepository;
use App\Repository\GajiPerBulanRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class GajiPerBulanController extends Controller
{
    private $param;
    private $orderRaw;
    private $cabang;

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
        $this->param['namaTunjangan'] = [
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
            'tj_fungsional',
            'tj_transport',
            'tj_pulsa',
            'tj_vitamin',
            'uang_makan',
        ];

        $this->cabang = CabangModel::get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getBulan(Request $request)
    {
        $tahun = $request->get('tahun');
        $is_cabang = auth()->user()->hasRole('cabang');
        $is_pusat = auth()->user()->hasRole('kepegawaian');
        $kd_cabang = DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();

        $bulan = DB::table('gaji_per_bulan')
            ->join('mst_karyawan AS m', 'm.nip', 'gaji_per_bulan.nip')
            ->where('tahun', $tahun)
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
            ->distinct()
            ->get('bulan');
        if (count($bulan) > 0) {
            return response()->json($bulan);
        } else {
            return null;
        }
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('penghasilan - proses penghasilan')) {
            return view('roles.forbidden');
        }

        $tab = $request->has('tab') ? $request->get('tab') : 'proses';
        $limit = 10;
        if ($request->has('tab') && $tab == 'proses') {
            $limit = $request->has('page_length_proses') ? $request->get('page_length_proses') : 10;
        } else if ($request->has('tab') && $tab == 'final') {
            $limit = $request->has('page_length_final') ? $request->get('page_length_final') : 10;
        } else {
            $limit = $request->has('page_length_sampah') ? $request->get('page_length_sampah') : 10;
        }

        $page = $request->has('page') ? $request->get('page') : 1;
        $tahun_req = $request->has('tahun') ? $request->get('tahun') : null;
        $bulan_req = $request->has('bulan') ? $request->get('bulan') : null;
        $cabang_req = $request->has('cabang') ? $request->get('cabang') : null;
        $search_proses = $request->get('q_proses');
        $search_final = $request->get('q_final');
        $search_sampah = $request->get('q_sampah');
        $search_proses = str_replace("'", "\'", $search_proses);
        $search_final = str_replace("'", "\'", $search_final);
        $search_sampah = str_replace("'", "\'", $search_sampah);
        $gajiRepo = new GajiPerBulanRepository;
        $cabang = $this->cabang;

        // Proses
        $proses_list = $gajiRepo->getPenghasilanList($tahun_req, $bulan_req, $cabang_req,'proses', $limit, ($request->has('tab') && $tab == 'proses') ? $page : 1, $search_proses);
        // Final
        $final_list = $gajiRepo->getPenghasilanList($tahun_req, $bulan_req, $cabang_req,'final', $limit, ($request->has('tab') && $tab == 'final') ? $page : 1, $search_final);
        // Sampah

        // Kode Jabatan direktur,komisaris & staf ahli
        $kd_divisi_non_pegawai = GajiPerBulanRepository::getDivisiNonPegawai();

        if(auth()->user()->hasRole('admin')) {
            $sampah = $gajiRepo->getPenghasilanTrash($tahun_req, $bulan_req, $cabang_req,null, $limit, ($request->has('tab') && $tab == 'sampah') ? $page : 1, $search_sampah);
            $data = [
                'cabang' => $cabang,
                'proses_list' => $proses_list,
                'final_list' => $final_list,
                'sampah' => $sampah,
                'kd_divisi_non_pegawai' => $kd_divisi_non_pegawai,
            ];
        }
        else{
            $data = [
                'proses_list' => $proses_list,
                'final_list' => $final_list,
                'cabang' => $cabang,
                'kd_divisi_non_pegawai' => $kd_divisi_non_pegawai,
            ];
        }

        return view('gaji_perbulan.index', $data);
    }

    public function getDataPenghasilanJson(Request $request) {
        $status = 'failed';
        $message = '';
        $data = null;

        try {
            $is_pegawai = true;
            $kd_divisi_selected = $request->get('divisi'); // Used for divisi direksi, komisaris & staf ahli
            if ($request->has('is_pegawai')) {
                $is_pegawai = $request->get('is_pegawai') == 'true';
            }

            $arr_kd_divisi = ['DIR', 'KOM', 'SAD'];
            $kd_divisi = DB::table('mst_divisi')
                        ->whereIn('kd_divisi', $arr_kd_divisi)
                        ->pluck('kd_divisi')
                        ->toArray();
            $is_cabang = auth()->user()->hasRole('cabang');
            $is_pusat = auth()->user()->hasRole('kepegawaian');
            $kd_cabang = DB::table('mst_cabang')
                            ->select('kd_cabang')
                            ->pluck('kd_cabang')
                            ->toArray();
            if ($is_cabang) {
                $kd_entitas = auth()->user()->kd_cabang;
            }
            else {
                $kd_entitas = '000';
            }

            // Get last penggajian
            $last_date_penggajian = DB::table('batch_gaji_per_bulan AS batch')
                                        ->select(
                                            'batch.*',
                                            'm.kd_entitas AS entitas_karyawan',
                                        )
                                        ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
                                        ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                                        ->where('batch.kd_entitas', $kd_entitas)
                                        ->where(function($query) use ($is_pegawai, $kd_divisi_selected) {
                                            if ($is_pegawai) {
                                                $query->where('batch.is_pegawai', $is_pegawai);
                                            }
                                            else {
                                                // Divisi direktur, komisaris & staf ahli
                                                $query->where('m.kd_entitas', $kd_divisi_selected);
                                            }
                                        })
                                        ->whereNull('batch.deleted_at')
                                        ->orderByDesc('batch.id')
                                        ->first('batch.tanggal_input');
            $last_month_penggajian = 1;
            if ($last_date_penggajian) {
                $last_month_penggajian = intval(date('m', strtotime($last_date_penggajian->tanggal_input)));
                $last_month_penggajian++;
            }
            // Get Karyawan
            $karyawan = DB::table('mst_karyawan AS m')
                            ->whereRaw("(tanggal_penonaktifan IS NULL OR ($last_month_penggajian = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                            ->when($is_cabang, function($query) {
                                $kd_cabang = auth()->user()->kd_cabang;
                                $query->where('m.kd_entitas', $kd_cabang);
                            })
                            ->when($is_pusat, function($query) use ($is_pegawai, $kd_cabang, $kd_divisi, $kd_divisi_selected) {
                                if ($is_pegawai) {
                                    $query->where(function($query2) use ($kd_divisi, $kd_cabang) {
                                        $query2->whereNotIn('m.kd_entitas', $kd_divisi)
                                            ->whereNotIn('m.kd_entitas', $kd_cabang)
                                            ->orWhereNull('m.kd_entitas');
                                    });
                                }
                                else {
                                    $query->where('m.kd_entitas', $kd_divisi_selected);
                                }
                            })
                            ->get();
            $total_karyawan = count($karyawan);

            // Get Bruto
            $id_tunjangan_teratur_arr = DB::table('mst_tunjangan')
                                    ->where('status', 1)
                                    ->where('kategori', 'teratur')
                                    ->pluck('id')
                                    ->toArray();
            $id_tunjangan_rutin_arr = DB::table('mst_tunjangan')
                                    ->where('status', 2)
                                    ->where('kategori', 'teratur')
                                    ->pluck('id')
                                    ->toArray();

            $bruto = 0;
            $potongan = 0;
            $total_potongan_kredit_koperasi = 0;
            $total_potongan_iuran_koperasi = 0;
            $total_potongan_kredit_pegawai = 0;
            $total_potongan_iuran_ik = 0;
            $sub_total_dpp = 0;
            $grandtotal_dpp = 0;
            $total_jp = 0;
            $sub_total_bpjs_tk = 0;

            foreach ($karyawan as $key => $value) {
                $kd_entitas = null;
                if (auth()->user()->kd_cabang && auth()->user()->kd_cabang != '000') {
                    $kd_entitas = auth()->user()->kd_cabang;
                }
                else {
                    $kd_entitas = '000';
                }
                $gaji_component = new GajiComponent($kd_entitas);
                $hitungan = $gaji_component->getMasterHitunganBruto();
                $hitungan_penambah = $hitungan->penambah;
                $hitungan_pengurang = $hitungan->pengurang;
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
                // Get bruto per karyawan
                $tunjangan = (int) DB::table('tunjangan_karyawan')
                                ->where('nip', $value->nip)
                                ->whereIn('id_tunjangan', $id_tunjangan_teratur_arr)
                                ->sum('nominal');
                $tj_keluarga = DB::table('tunjangan_karyawan')
                                ->where('nip', $value->nip)
                                ->where('id_tunjangan', 1)
                                ->first('nominal');
                if ($tj_keluarga) {
                    $tj_keluarga = intval($tj_keluarga->nominal);
                }
                else {
                    $tj_keluarga = 0;
                }
                $tj_kesejahteraan = DB::table('tunjangan_karyawan')
                                    ->where('nip', $value->nip)
                                    ->where('id_tunjangan', 8)
                                    ->first('nominal');
                if ($tj_kesejahteraan) {
                    $tj_kesejahteraan = intval($tj_kesejahteraan->nominal);
                }
                else {
                    $tj_kesejahteraan = 0;
                }
                $tunjangan_rutin = (int) DB::table('tunjangan_karyawan')
                                ->where('nip', $value->nip)
                                ->whereIn('id_tunjangan', $id_tunjangan_rutin_arr)
                                ->sum('nominal');

                $bruto_karyawan = $tunjangan + $value->gj_pokok + $value->gj_penyesuaian;
                $total_gaji = $bruto_karyawan + $tunjangan_rutin;
                $bruto += $bruto_karyawan;

                // Get potongan per karyawan
                $potongan_karyawan_obj = DB::table('potongan_gaji')
                                            ->select(
                                                'kredit_koperasi',
                                                'iuran_koperasi',
                                                'kredit_pegawai',
                                                'iuran_ik',
                                                DB::raw('(kredit_koperasi + iuran_koperasi + kredit_pegawai + iuran_ik) AS potongan')
                                            )
                                            ->where('nip', $value->nip)
                                            ->first();

                $potongan_karyawan = 0;
                $potongan_kredit_koperasi = 0;
                $potongan_iuran_koperasi = 0;
                $potongan_kredit_pegawai = 0;
                $potongan_iuran_ik = 0;
                if ($potongan_karyawan_obj) {
                    $potongan_karyawan = (int) $potongan_karyawan_obj->potongan;
                    $potongan_kredit_koperasi = (int) $potongan_karyawan_obj->kredit_koperasi;
                    $potongan_iuran_koperasi = (int) $potongan_karyawan_obj->iuran_koperasi;
                    $potongan_kredit_pegawai = (int) $potongan_karyawan_obj->kredit_pegawai;
                    $potongan_iuran_ik = (int) $potongan_karyawan_obj->iuran_ik;
                }

                // Get DPP
                $dpp = 0;
                if ($kd_divisi_selected != 'DIR' && $kd_divisi_selected != 'KOM') {
                    $dpp = $gaji_component->getDPP($value->status_karyawan, $value->gj_pokok, $tj_keluarga, $tj_kesejahteraan);
                }
                $bulan = date('m');

                // Get BPJS TK
                $bpjs_tk = $gaji_component->getBPJSTK($value->kpj, $total_gaji, $bulan);

                $potongan += $potongan_karyawan + $dpp + $bpjs_tk;
                $sub_total_dpp += $dpp;
                $sub_total_bpjs_tk += $bpjs_tk;
                $total_potongan_kredit_koperasi += $potongan_kredit_koperasi;
                $total_potongan_iuran_koperasi += $potongan_iuran_koperasi;
                $total_potongan_kredit_pegawai += $potongan_kredit_pegawai;
                $total_potongan_iuran_ik += $potongan_iuran_ik;
            }

            // Get Netto
            $netto = $bruto - floor($potongan);

            // Get Penghasilan terakhir
            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
            $penghasilan = DB::table('batch_gaji_per_bulan AS batch')
                            ->when($is_pegawai, function($query) use ($is_pegawai, $kd_divisi_selected) {
                                $query->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
                                ->join('mst_karyawan AS karyawan', 'karyawan.nip', 'gaji.nip')
                                ->where(function($query) use ($is_pegawai, $kd_divisi_selected) {
                                    if (!$is_pegawai) {
                                        $query->where('karyawan.kd_entitas', $kd_divisi_selected);
                                    }
                                });
                            })
                            ->where('batch.kd_entitas', $kd_entitas)
                            ->where('batch.is_pegawai', $is_pegawai)
                            ->whereYear('batch.tanggal_input', date('Y'))
                            ->whereNull('batch.deleted_at')
                            ->orderBy('batch.tanggal_input', 'DESC')
                            ->first();

            $penghasilan_tahun_terakhir = date('Y');
            $penghasilan_bulan_terakhir = 0;
            if ($penghasilan) {
                $penghasilan_tahun_terakhir = (int) date('Y', strtotime($penghasilan->tanggal_input));
                $penghasilan_bulan_terakhir = (int) date('m', strtotime($penghasilan->tanggal_input));
            }

            $data = [
                'total_karyawan' => $total_karyawan,
                'bruto' => $bruto,
                'potongan' => floor($potongan),
                'potongan_kredit_koperasi' => floor($total_potongan_kredit_koperasi),
                'potongan_iuran_koperasi' => floor($total_potongan_iuran_koperasi),
                'potongan_kredit_pegawai' => floor($total_potongan_kredit_pegawai),
                'potongan_iuran_ik' => floor($total_potongan_iuran_ik),
                'potongan_dpp' => floor($sub_total_dpp),
                'potongan_bpjs_tk' => floor($sub_total_bpjs_tk),
                'netto' => $netto,
                'penghasilan_tahun_terakhir' => $penghasilan_tahun_terakhir,
                'penghasilan_bulan_terakhir' => $penghasilan_bulan_terakhir,
            ];

            $status = 'success';
            $message = 'Berhasil mengambil data';
        }
        catch (Exception $e) {
            $status = 'error';
            $message = $e->getMessage();
        }
        finally {
            $response = [
                'status' => $status,
                'message' => $message,
                'data' => $data
            ];

            return response()->json($response);
        }
    }

    public function penyesuaianDataJson(Request $request) {
        try {
            $batch_id = $request->batch_id;
            $data_gaji = DB::table('gaji_per_bulan AS gaji')
                            ->select(
                                'batch.tanggal_input',
                                'gaji.*',
                                'm.nama_karyawan',
                                'm.status_karyawan',
                                DB::raw('CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_multilevel + gaji.tj_ti) AS SIGNED) AS total_penghasilan'),
                                DB::raw('CAST((gaji.kredit_koperasi + gaji.iuran_koperasi + gaji.kredit_pegawai + gaji.iuran_ik + gaji.dpp + gaji.bpjs_tk) AS SIGNED) AS total_potongan')
                            )
                            ->join('batch_gaji_per_bulan AS batch', 'batch.id', 'gaji.batch_id')
                            ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                            ->where('gaji.batch_id', $batch_id)
                            ->get();

            $totalBruto = 0;
            $totalBrutoBaru = 0;
            $totalPotongan = 0;
            $totalPotonganBaru = 0;
            foreach ($data_gaji as $key => $gaji) {
                $kd_entitas = null;
                if (auth()->user()->kd_cabang && auth()->user()->kd_cabang != '000') {
                    $kd_entitas = auth()->user()->kd_cabang;
                }
                else {
                    $kd_entitas = '000';
                }
                $new_data = [];
                $total_penghasilan_baru = $gaji->total_penghasilan;
                $total_potongan_baru = $gaji->total_potongan;

                $karyawan = DB::table('mst_karyawan')
                            ->where('nip', $gaji->nip)
                            ->first();
                $total_gaji_baru = $karyawan->gj_pokok + $karyawan->gj_penyesuaian;
                $tj_keluarga_baru = 0;
                $tj_kesejahteraan_baru = 0;
                if ($gaji->gj_pokok != $karyawan->gj_pokok) {
                    $total_penghasilan_baru -= $gaji->gj_pokok;
                    $total_penghasilan_baru += $karyawan->gj_pokok;
                    $item = [
                        'gj_pokok' => $gaji->gj_pokok,
                        'gj_pokok_baru' => $karyawan->gj_pokok,
                    ];
                    array_push($new_data, $item);
                }
                if ($gaji->gj_penyesuaian != $karyawan->gj_penyesuaian) {
                    $total_penghasilan_baru -= $gaji->gj_penyesuaian;
                    $total_penghasilan_baru += $karyawan->gj_penyesuaian;
                    $item = [
                        'gj_penyesuaian' => $gaji->gj_penyesuaian,
                        'gj_penyesuaian_baru' => $karyawan->gj_penyesuaian,
                    ];
                    array_push($new_data, $item);
                }

                $tunjangan = DB::table('tunjangan_karyawan')
                                ->where('nip', $gaji->nip)
                                ->get();
                foreach ($tunjangan as $tunj) {
                    // Keluarga
                    if ($tunj->id_tunjangan == 1) {
                        if ($gaji->tj_keluarga != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_keluarga;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_keluarga' => $gaji->tj_keluarga,
                                'tj_keluarga_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            $tj_keluarga_baru = $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_keluarga;
                            $tj_keluarga_baru = $gaji->tj_keluarga;
                        }
                    }
                    // Telepon
                    if ($tunj->id_tunjangan == 2) {
                        if ($gaji->tj_telepon != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_telepon;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_telepon' => $gaji->tj_telepon,
                                'tj_telepon_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_telepon;
                        }
                    }
                    // Jabatan
                    if ($tunj->id_tunjangan == 3) {
                        if ($gaji->tj_jabatan != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_jabatan;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_jabatan' => $gaji->tj_jabatan,
                                'tj_jabatan_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_jabatan;
                        }
                    }
                    // Teller
                    if ($tunj->id_tunjangan == 4) {
                        if ($gaji->tj_teller != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_teller;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_teller' => $gaji->tj_teller,
                                'tj_teller_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_teller;
                        }
                    }
                    // Perumahan
                    if ($tunj->id_tunjangan == 5) {
                        if ($gaji->tj_perumahan != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_perumahan;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_perumahan' => $gaji->tj_perumahan,
                                'tj_perumahan_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_perumahan;
                        }
                    }
                    // Kemahalan
                    if ($tunj->id_tunjangan == 6) {
                        if ($gaji->tj_kemahalan != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_kemahalan;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_kemahalan' => $gaji->tj_kemahalan,
                                'tj_kemahalan_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_kemahalan;
                        }
                    }
                    // Pelaksana
                    if ($tunj->id_tunjangan == 7) {
                        if ($gaji->tj_pelaksana != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_pelaksana;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_pelaksana' => $gaji->tj_pelaksana,
                                'tj_pelaksana_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_pelaksana;
                        }
                    }
                    // Kesejahteraan
                    if ($tunj->id_tunjangan == 8) {
                        if ($gaji->tj_kesejahteraan != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_kesejahteraan;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_kesejahteraan' => $gaji->tj_kesejahteraan,
                                'tj_kesejahteraan_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            $tj_kesejahteraan_baru = $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_kesejahteraan;
                            $tj_kesejahteraan_baru = $gaji->tj_kesejahteraan;
                        }
                    }
                    // Multilevel
                    if ($tunj->id_tunjangan == 9) {
                        if ($gaji->tj_multilevel != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_multilevel;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_multilevel' => $gaji->tj_multilevel,
                                'tj_multilevel_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_multilevel;
                        }
                    }
                    // TI
                    if ($tunj->id_tunjangan == 10) {
                        if ($gaji->tj_ti != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_ti;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_ti' => $gaji->tj_ti,
                                'tj_ti_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_ti;
                        }
                    }
                    // Fungsional
                    if ($tunj->id_tunjangan == 30) {
                        if ($gaji->tj_fungsional != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_fungsional;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_fungsional' => $gaji->tj_fungsional,
                                'tj_fungsional_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_fungsional;
                        }
                    }
                }

                $transaksi_tunjangan = DB::table('transaksi_tunjangan')
                                        ->where('nip', $gaji->nip)
                                        ->whereYear('tanggal', $gaji->tahun)
                                        ->where(function($query) use ($gaji, $kd_entitas) {
                                            $bulan = $gaji->bulan;
                                            $tahun = $gaji->tahun;
                                            $tanggal = $gaji->tanggal_input;
                                            $day = date('d', strtotime($tanggal));
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
                            $total_penghasilan_baru -= $gaji->tj_transport;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_transport' => $gaji->tj_transport,
                                'tj_transport_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_transport;
                        }
                    }
                    // Pulsa
                    if ($tunj->id_tunjangan == 12) {
                        if ($gaji->tj_pulsa != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_pulsa;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_pulsa' => $gaji->tj_pulsa,
                                'tj_pulsa_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_pulsa;
                        }
                    }
                    // Vitamin
                    if ($tunj->id_tunjangan == 13) {
                        if ($gaji->tj_vitamin != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->tj_vitamin;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'tj_vitamin' => $gaji->tj_vitamin,
                                'tj_vitamin_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->tj_vitamin;
                        }
                    }
                    // Uang Makan
                    if ($tunj->id_tunjangan == 14) {
                        if ($gaji->uang_makan != $tunj->nominal) {
                            $total_penghasilan_baru -= $gaji->uang_makan;
                            $total_penghasilan_baru += $tunj->nominal;
                            $item = [
                                'uang_makan' => $gaji->uang_makan,
                                'uang_makan_baru' => $tunj->nominal,
                            ];
                            $total_gaji_baru += $tunj->nominal;
                            array_push($new_data, $item);
                        }
                        else {
                            $total_gaji_baru += $gaji->uang_makan;
                        }
                    }
                }

                // Get Penghasilan Tidak Rutin
                $penghasilanTidakRutin = DB::table('penghasilan_tidak_teratur AS p')
                                        ->select(
                                            'p.id',
                                            'p.id_tunjangan',
                                            'm.nama_tunjangan',
                                            'p.nominal',
                                        )
                                        ->join('mst_tunjangan AS m', 'm.id', 'p.id_tunjangan')
                                        ->where('p.nip', $gaji->nip)
                                        ->whereYear('p.created_at', $gaji->tahun)
                                        ->where(function($query) use ($gaji, $kd_entitas) {
                                            $bulan = $gaji->bulan;
                                            $tahun = $gaji->tahun;
                                            $tanggal = $gaji->tanggal_input;
                                            $day = date('d', strtotime($tanggal));
                                            if ($bulan > 1) {
                                                // Tanggal penggajian bulan sebelumnya
                                                $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                                $query->whereBetween('p.created_at', [$start_date, $tanggal]);
                                            }
                                            else if ($bulan == 12) {
                                                $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                                $last_day = getLastDateOfMonth($tahun, $bulan);
                                                $end_date = $tahun.'-'.$bulan.'-'.$last_day;
                                                $query->whereBetween('p.created_at', [$start_date, $end_date]);
                                            }
                                            else {
                                                $query->whereDay('p.created_at', '<=', $day);
                                            }
                                        })
                                        ->get();

                foreach ($penghasilanTidakRutin as $tidakRutin) {
                    $nominalLama = 0;
                    $nominalBaru = 0;
                    $current = DB::table('batch_penghasilan_tidak_teratur')
                                    ->where('gaji_per_bulan_id', $gaji->id)
                                    ->where('penghasilan_tidak_teratur_id', $tidakRutin->id)
                                    ->first();
                    if ($current) {
                        $nominalLama = (int) $current->nominal;
                        $nominalBaru = (int) $tidakRutin->nominal;
                        if ($nominalLama != $nominalBaru) {
                            $item_title = str_replace(' ', '_', strtolower($tidakRutin->nama_tunjangan));
                            $item_title_new = $item_title.'_baru';
                            $item = [
                                $item_title => $nominalLama,
                                $item_title_new => $nominalBaru,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    else {
                        $nominalBaru = (int) $tidakRutin->nominal;
                        $item_title = str_replace(' ', '_', strtolower($tidakRutin->nama_tunjangan));
                        $item_title_new = $item_title.'_baru';
                        $item = [
                            $item_title => $nominalLama,
                            $item_title_new => $nominalBaru,
                        ];
                        array_push($new_data, $item);
                    }

                    $total_penghasilan_baru -= $nominalLama;
                    $total_penghasilan_baru += $nominalBaru;
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
                        $nominalLama = (int) $batchTidakRutin->nominal;
                        $nominalBaru = 0;
                        $item_title = str_replace(' ', '_', strtolower($batchTidakRutin->nama_tunjangan));
                        $item_title_new = $item_title.'_baru';
                        $item = [
                            $item_title => $nominalLama,
                            $item_title_new => $nominalBaru,
                        ];
                        array_push($new_data, $item);

                        $total_penghasilan_baru -= $nominalLama;
                        $total_penghasilan_baru += $nominalBaru;
                    }
                }

                $totalBrutoBaru += $total_penghasilan_baru;

                // Get Potongan
                $potongan = DB::table('potongan_gaji')
                            ->select(
                                'potongan_gaji.*',
                                DB::raw('(kredit_koperasi + iuran_koperasi + kredit_pegawai + iuran_ik) AS total_potongan'),
                            )
                            ->where('nip', $gaji->nip)
                            ->first();

                if ($potongan) {
                    if ($potongan->kredit_koperasi != $gaji->kredit_koperasi) {
                        $total_potongan_baru -= $gaji->kredit_koperasi;
                        $total_potongan_baru += $potongan->kredit_koperasi;
                        $item = [
                            'potongan_kredit_koperasi' => $gaji->kredit_koperasi,
                            'potongan_kredit_koperasi_baru' => floatval(number_format($potongan->kredit_koperasi, 2, '.', '')),
                        ];
                        array_push($new_data, $item);
                    }
                    if ($potongan->iuran_koperasi != $gaji->iuran_koperasi) {
                        $total_potongan_baru -= $gaji->iuran_koperasi;
                        $total_potongan_baru += $potongan->iuran_koperasi;
                        $item = [
                            'potongan_iuran_koperasi' => $gaji->iuran_koperasi,
                            'potongan_iuran_koperasi_baru' => floatval(number_format($potongan->iuran_koperasi, 2, '.', '')),
                        ];
                        array_push($new_data, $item);
                    }
                    if ($potongan->kredit_pegawai != $gaji->kredit_pegawai) {
                        $total_potongan_baru -= $gaji->kredit_pegawai;
                        $total_potongan_baru += $potongan->kredit_pegawai;
                        $item = [
                            'potongan_kredit_pegawai' => $gaji->kredit_pegawai,
                            'potongan_kredit_pegawai_baru' => floatval(number_format($potongan->kredit_pegawai, 2, '.', '')),
                        ];
                        array_push($new_data, $item);
                    }
                    if ($potongan->iuran_ik != $gaji->iuran_ik) {
                        $total_potongan_baru -= $gaji->iuran_ik;
                        $total_potongan_baru += $potongan->iuran_ik;
                        $item = [
                            'potongan_iuran_ik' => $gaji->iuran_ik,
                            'potongan_iuran_ik_baru' => floatval(number_format($potongan->iuran_ik, 2, '.', '')),
                        ];
                        array_push($new_data, $item);
                    }
                }

                // Potongan DPP & BPJS TK
                $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                    ->where('mst_profil_kantor.kd_cabang', $kd_entitas)
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

                // Get DPP
                $dpp = 0;
                $jp_1_persen = 0;
                $bulan = intval($gaji->bulan);
                $nominal_jp = ($bulan > 2) ? $jp_mar_des : $jp_jan_feb;
                if($gaji->status_karyawan == 'IKJP' || $gaji->status_karyawan == 'Kontrak Perpanjangan') {
                    $dpp = ($persen_jp_pengurang / 100) * $total_gaji_baru;
                }
                else {
                    // Get DPP
                    $dpp = round(((($karyawan->gj_pokok + $tj_keluarga_baru) + ($tj_kesejahteraan_baru * 0.5)) * 0.05));
                    // Get JP 1%
                    $jp_1_persen = floor($total_gaji_baru * ($persen_jp_pengurang / 100));
                    if($total_gaji_baru >= $nominal_jp){
                        $jp_1_persen = floor($nominal_jp * ($persen_jp_pengurang / 100));
                    } else {
                        $jp_1_persen = floor($total_gaji_baru * ($persen_jp_pengurang / 100));
                    }
                }
                $dpp = round($dpp);

                if ($dpp != $gaji->dpp) {
                    $total_potongan_baru -= $gaji->dpp;
                    $total_potongan_baru += $dpp;
                    $item = [
                        'potongan_dpp' => $gaji->dpp,
                        'potongan_dpp_baru' => $dpp,
                    ];
                    array_push($new_data, $item);
                }

                // Get BPJS TK
                $jp_persen = $persen_jp_pengurang / 100;
                $bpjs_tk = 0;
                if ($karyawan->kpj) {
                    if ($bulan > 2) {
                        if ($total_gaji_baru > $jp_mar_des) {
                            $bpjs_tk = $jp_mar_des * $jp_persen;
                        }
                        else {
                            $bpjs_tk = $total_gaji_baru * $jp_persen;
                        }
                    }
                    else {
                        if ($total_gaji_baru >= $jp_jan_feb) {
                            $bpjs_tk = $jp_jan_feb * $jp_persen;
                        }
                        else {
                            $bpjs_tk = $total_gaji_baru * $jp_persen;
                        }
                    }
                    $bpjs_tk = round($bpjs_tk);
                }
                if ($bpjs_tk != $gaji->bpjs_tk) {
                    $total_potongan_baru -= $gaji->bpjs_tk;
                    $total_potongan_baru += $bpjs_tk;
                    $item = [
                        'potongan_bpjs_tk' => $gaji->bpjs_tk,
                        'potongan_bpjs_tk_baru' => $bpjs_tk,
                    ];
                    array_push($new_data, $item);
                }

                $totalPotonganBaru += $total_potongan_baru;
                $totalPotongan += $gaji->total_potongan;
                $totalBruto += $gaji->total_penghasilan;

                if (count($new_data) == 0) {
                    unset($data_gaji[$key]);
                } else {
                    $gaji->penyesuaian = $new_data;
                    $gaji->total_penghasilan_baru = $total_penghasilan_baru;
                    $gaji->total_potongan_baru = $total_potongan_baru;
                }
            }
            $grandtotal = [
                'bruto_lama' => (int) $totalBruto,
                'bruto_baru' => (int) $totalBrutoBaru,
                'potongan_lama' => (float) number_format($totalPotongan, 2, '.', ''),
                'potongan_baru' => (float) number_format($totalPotonganBaru, 2, '.', ''),
                'netto_lama' => intval($totalBruto - $totalPotongan),
                'netto_baru' => intval($totalBrutoBaru - $totalPotonganBaru),
            ];
            return DataTables::of($data_gaji)
                            ->addColumn('counter', function ($row) {
                                static $count = 0;
                                $count++;
                                return $count;
                            })
                            ->addColumn('grandtotal', function ($row) use($grandtotal) {
                                return $grandtotal;
                            })
                            ->rawColumns(['counter', 'grandtotal'])
                            ->make(true);
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function penghasilanKantor() {
        $status = 'failed';
        $message = '';

        try {
            $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
            $kantor = DB::table('mst_cabang')->orderBy('kd_cabang')->get();
            $kd_cabang_arr = DB::table('mst_cabang')
                                ->orderBy('kd_cabang')
                                ->pluck('kd_cabang')
                                ->toArray();

            foreach ($kantor as $value) {
                $penghasilan = new \stdClass();
                foreach ($months as $key => $m) {
                    if ($value->kd_cabang == '000') {
                        // Pusat
                        $batch = DB::table('batch_gaji_per_bulan AS batch')
                                    ->select('batch.status')
                                    ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
                                    ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                                    ->whereMonth('batch.tanggal_input', $key)
                                    ->where(function($query) use($kd_cabang_arr) {
                                        $query->whereNotIn('m.kd_entitas', $kd_cabang_arr)
                                            ->orWhereNull('m.kd_entitas');
                                    })
                                    ->first();
                    }
                    else {
                        // Cabang
                        $batch = DB::table('batch_gaji_per_bulan AS batch')
                                    ->select('batch.status')
                                    ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
                                    ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                                    ->whereMonth('batch.tanggal_input', $key)
                                    ->where('m.kd_entitas', $value->kd_cabang)
                                    ->first();
                    }

                    $month = strtolower($m);
                    if ($batch) {
                        $penghasilan->$month = $batch->status;
                    }
                    else {
                        $penghasilan->$month = '-';
                    }
                }
                $value->penghasilan = $penghasilan;
            }

            return DataTables::of($kantor)
                            ->addColumn('counter', function ($row) {
                                static $count = 0;
                                $count++;
                                return $count;
                            })
                            ->rawColumns(['counter'])
                            ->make(true);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json([
                'status' => $status,
                'message' => $message,
            ]);
        }
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
        if (!auth()->user()->can('penghasilan - pajak penghasilan')) {
            return view('roles.forbidden');
        }

        DB::beginTransaction();
        try {
            $is_pegawai = true;
            if ($request->has('is_pegawai')) {
                $is_pegawai = $request->get('is_pegawai') == 'true';
            }

            if ($request->has('batch_id')) {
                $batch = DB::table('batch_gaji_per_bulan')->find($request->batch_id);
                $bulan = (int) date('m', strtotime($batch->tanggal_input));
                $tahun = date('Y', strtotime($batch->tanggal_input));
                $tanggal = $batch->tanggal_input;
            }
            else {
                $this->validate($request, [
                    'tanggal' => 'required',
                ], [
                    'required' => ':attribute harus diisi.',
                ], [
                    'tanggal' => 'Tanggal',
                ]);

                $tanggal = $request->tanggal;
                $bulan = (int) date('m', strtotime($tanggal));
                $tahun = (int) date('Y', strtotime($tanggal));
            }

            $selected_divisi = $request->get('divisi');
            $day = date('d', strtotime($tanggal));
            $tunjangan = array();
            $tjJamsostek = array();
            $cabang = DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();

            $now = date('Y-m-d H:i:s');
            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
            if ($request->has('batch_id')) {
                $batch = [
                    'updated_at' => $now,
                ];
                DB::table('batch_gaji_per_bulan')->update($batch);
            }
            else {
                $batch = [
                    'kd_entitas' => $kd_entitas,
                    'is_pegawai' => $is_pegawai,
                    'tanggal_input' => $tanggal,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $batch_id = DB::table('batch_gaji_per_bulan')->insertGetId($batch);
            }

            if (auth()->user()->hasRole('cabang')) {
                // Cabang
                // Get last penggajian
                $last_date_penggajian = GajiPerBulanRepository::getLastPenggajianCurrentYear(auth()->user()->kd_cabang, true);
                $last_month_penggajian = 0;
                if ($last_date_penggajian) {
                    $last_month_penggajian = intval(date('m', strtotime($last_date_penggajian->tanggal_input)));
                }
                $karyawan = DB::table('mst_karyawan')
                            ->whereRaw("(tanggal_penonaktifan IS NULL OR ($last_month_penggajian = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                            ->where('kd_entitas', auth()->user()->kd_cabang)
                            ->get();
            }
            else {
                $is_pusat = auth()->user()->hasRole('kepegawaian');
                $kd_cabang = DB::table('mst_cabang')
                                ->select('kd_cabang')
                                ->pluck('kd_cabang')
                                ->toArray();

                $kd_divisi = DB::table('mst_divisi')
                                ->whereIn('kd_divisi', ['DIR', 'KOM', 'SAD'])
                                ->pluck('kd_divisi')
                                ->toArray();
                // Get last penggajian
                $last_date_penggajian = GajiPerBulanRepository::getLastPenggajianCurrentYear($kd_entitas, $is_pegawai);
                $last_month_penggajian = 0;
                if ($last_date_penggajian) {
                    $last_month_penggajian = intval(date('m', strtotime($last_date_penggajian->tanggal_input)));
                }

                $karyawan = DB::table('mst_karyawan')
                                ->whereRaw("(tanggal_penonaktifan IS NULL OR ($last_month_penggajian = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                                ->when($is_pusat, function($query) use ($kd_cabang, $kd_divisi, $selected_divisi, $is_pegawai) {
                                    if ($is_pegawai) {
                                        $query->where(function($query2) use ($kd_divisi, $kd_cabang) {
                                            $query2->whereNotIn('kd_entitas', $kd_divisi)
                                                ->whereNotIn('kd_entitas', $kd_cabang)
                                                ->orWhereNull('kd_entitas');
                                        });
                                    }
                                    else {
                                        $query->where('kd_entitas', $selected_divisi);
                                    }
                                })
                                ->get();
            }

            // Get Penghasilan from mst_karyawan + tunjangan karyawan + penghasilan tidak teratur
            $item_penghasilan_teratur = TunjanganModel::select('id','nama_tunjangan', 'kategori', 'status')
                                                    ->where('kategori', 'teratur')
                                                    ->orWhereNull('kategori')
                                                    ->orderBy('id')
                                                    ->get();
            foreach ($karyawan as $item) {
                unset($tunjangan);
                unset($tjJamsostek);
                $tjJamsostek = array();
                $tunjangan = array();

                // Get tunjangan karyawan
                foreach ($item_penghasilan_teratur as $tunj) {
                    if ($tunj->status == 1 || $tunj->kategori == null) {
                        // GET Tunjangan (THP)
                        $tj = DB::table('tunjangan_karyawan')
                                ->where('nip', $item->nip)
                                ->where('id_tunjangan', $tunj->id)
                                ->first();
                        array_push($tunjangan, ($tj != null) ? $tj->nominal : 0);
                        if ($tunj->status) {
                            array_push($tjJamsostek, ($tj != null) ? $tj->nominal : 0);
                        }
                    }
                    else {
                        $tj = DB::table('transaksi_tunjangan')
                                ->where('nip', $item->nip)
                                ->where('id_tunjangan', $tunj->id)
                                ->whereYear('tanggal', $tahun)
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
                                ->first();
                        array_push($tunjangan, ($tj != null) ? $tj->nominal : 0);
                        if ($tunj->status) {
                            array_push($tjJamsostek, ($tj != null) ? $tj->nominal : 0);
                        }
                    }
                }

                $ptkp = HitungPPH::getPTKP($item);

                // Get penambah & pengurang bruto
                $gaji_component = new GajiComponent($item->kd_entitas);
                $hitungan = $gaji_component->getMasterHitunganBruto();
                $hitungan_penambah = $hitungan->penambah;
                $hitungan_pengurang = $hitungan->pengurang;

                $this->param['persenJkk'] = $hitungan_penambah->jkk;
                $this->param['persenJht'] = $hitungan_penambah->jht;
                $this->param['persenJkm'] = $hitungan_penambah->jkm;
                $this->param['persenKesehatan'] = $hitungan_penambah->kesehatan;
                $this->param['persenJpPenambah'] = $hitungan_penambah->jp;
                $this->param['persenDpp'] = $hitungan_pengurang->dpp;
                $this->param['persenJpPengurang'] = $hitungan_pengurang->jp;
                $this->param['batasAtas'] = $hitungan_penambah->kesehatan_batas_atas;
                $this->param['batasBawah'] = $hitungan_penambah->kesehatan_batas_bawah;
                $this->param['jpJanFeb'] = $hitungan_pengurang->jp_jan_feb;
                $this->param['jpMarDes'] = $hitungan_pengurang->jp_mar_des;
                $this->param['nominalJp'] = 0;

                // Get potongan
                $kredit_koperasi = 0;
                $iuran_koperasi = 0;
                $kredit_pegawai = 0;
                $iuran_ik = 0;
                $potongan_karyawan = DB::table('potongan_gaji')
                                        ->where('nip', $item->nip)
                                        ->first();
                if ($potongan_karyawan) {
                    $kredit_koperasi = $potongan_karyawan->kredit_koperasi;
                    $iuran_koperasi = $potongan_karyawan->iuran_koperasi;
                    $kredit_pegawai = $potongan_karyawan->kredit_pegawai;
                    $iuran_ik = $potongan_karyawan->iuran_ik;
                }

                $total_gaji = $item->gj_pokok + $item->gj_penyesuaian +  $tunjangan[0] + $tunjangan[1] + $tunjangan[2] + $tunjangan[3] + $tunjangan[4] + $tunjangan[6] + $tunjangan[5] + $tunjangan[7] + $tunjangan[8] + $tunjangan[9] + $tunjangan[15];
                $tunjangan_rutin = $tunjangan[10] + $tunjangan[11] + $tunjangan[12] + $tunjangan[13];

                // Get DPP
                $dpp = 0;
                if ($selected_divisi != 'DIR' && $selected_divisi != 'KOM') {
                    $dpp = $gaji_component->getDPP($item->status_karyawan, $item->gj_pokok, $tunjangan[0], $tunjangan[7]);
                }

                // Get BPJS TK
                $bpjs_tk = $gaji_component->getBPJSTK($item->kpj, $total_gaji, $bulan);
                $bpjs_tk_two = $gaji_component->getBPJSTK($item->kpj, $total_gaji, $bulan, false, true);

                // Get Penambah Bruto Jamsostek
                $jkk = $gaji_component->getJKK($item->kpj, $total_gaji);
                $jht = $gaji_component->getJHT($item->kpj, $total_gaji);
                $jkm = $gaji_component->getJKM($item->kpj, $total_gaji);
                $kesehatan = $gaji_component->getKesehatan($item->kpj, $total_gaji);
                $jp_penambah = $gaji_component->getJPPenambah($item->kpj, $total_gaji);
                $jamsostek = $gaji_component->getPenambahBrutoJamsostek($item->kpj, $item->jkn, $total_gaji);

                // Get Penghasilan Tidak Rutin
                $penghasilanTidakRutin = DB::table('penghasilan_tidak_teratur')
                                            ->select('id', 'id_tunjangan', 'nominal')
                                            ->where('nip', $item->nip)
                                            ->where('tahun', (int) $tahun)
                                            ->when($bulan, function($query) use ($bulan, $tanggal, $kd_entitas) {
                                                if ($bulan > 1) {
                                                    // Penggajian bulan sebelumnya
                                                    $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                                    $query->whereBetween('created_at', [$start_date, $tanggal]);
                                                }
                                                else if ($bulan == 12) {
                                                    // Penggajian bulan sebelumnya
                                                    $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                                    $currentMonth = intval(date('m', strtotime($tanggal)));
                                                    $currentYear = date('Y', strtotime($tanggal));
                                                    $last_day = getLastDateOfMonth($currentYear, $currentMonth);
                                                    $end_date = $currentYear.'-'.$currentMonth.'-'.$last_day;
                                                    $query->whereBetween('created_at', [$start_date, $end_date]);
                                                }
                                                else {
                                                    $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($tanggal)));
                                                }
                                            })
                                            ->get();

                if ($request->has('batch_id')) {
                    $employee = [
                        'gj_pokok' => $item->gj_pokok,
                        'gj_penyesuaian' => $item->gj_penyesuaian,
                        'tj_keluarga' => $tunjangan[0],
                        'tj_telepon' => $tunjangan[1],
                        'tj_jabatan' => $tunjangan[2],
                        'tj_teller' => $tunjangan[3],
                        'tj_perumahan' => $tunjangan[4],
                        'tj_kemahalan' => $tunjangan[5],
                        'tj_pelaksana' => $tunjangan[6],
                        'tj_kesejahteraan' => $tunjangan[7],
                        'tj_multilevel' => $tunjangan[8],
                        'tj_ti' => $tunjangan[9],
                        'tj_transport' => $tunjangan[10],
                        'tj_pulsa' => $tunjangan[11],
                        'tj_vitamin' => $tunjangan[12],
                        'uang_makan' => $tunjangan[13],
                        'dpp' => $dpp,
                        'bpjs_tk' => $bpjs_tk,
                        'bpjs_tk_two' => $bpjs_tk_two,
                        'jkk' => $jkk,
                        'jht' => $jht,
                        'jkm' => $jkm,
                        'jkm' => $jkm,
                        'kesehatan' => $kesehatan,
                        'jp' => $jp_penambah,
                        'penambah_bruto_jamsostek' =>  $jamsostek,
                        'tj_fungsional' => $tunjangan[15],
                        'updated_at' => $now,
                        'kredit_koperasi' => $kredit_koperasi,
                        'iuran_koperasi' => $iuran_koperasi,
                        'kredit_pegawai' => $kredit_pegawai,
                        'iuran_ik' => $iuran_ik,
                    ];
                    $gaji = GajiPerBulanModel::select('gaji_per_bulan.*', 'batch_gaji_per_bulan.deleted_at')
                                                ->join('batch_gaji_per_bulan', function($join) use ($kd_entitas, $bulan, $tahun) {
                                                    $join->on('batch_gaji_per_bulan.id', 'gaji_per_bulan.batch_id')
                                                        ->where('batch_gaji_per_bulan.kd_entitas', $kd_entitas)
                                                        ->whereMonth('batch_gaji_per_bulan.tanggal_input', $bulan)
                                                        ->whereYear('batch_gaji_per_bulan.tanggal_input', $tahun);
                                                })
                                                ->where('gaji_per_bulan.batch_id', $request->batch_id)
                                                ->whereNull('batch_gaji_per_bulan.deleted_at')
                                                ->where('gaji_per_bulan.nip', $item->nip)
                                                ->where('gaji_per_bulan.bulan', $bulan)
                                                ->where('gaji_per_bulan.tahun', $tahun)
                                                ->first();
                    GajiPerBulanModel::where('batch_id', $request->batch_id)
                                        ->where('nip', $item->nip)
                                        ->where('bulan', $bulan)
                                        ->where('tahun', $tahun)
                                        ->update($employee);

                    // Update Batch Penghasilan Tidak Rutin
                    foreach ($penghasilanTidakRutin as $tidakRutin) {
                        // Check if already stored on batch_penghasilan_tidak_teratur table
                        $current = DB::table('batch_penghasilan_tidak_teratur')
                                        ->where('gaji_per_bulan_id', $gaji->id)
                                        ->where('penghasilan_tidak_teratur_id', $tidakRutin->id)
                                        ->first();
                        if ($current) {
                            // Update
                            $batch_tidak_rutin = [
                                'gaji_per_bulan_id' => $gaji?->id,
                                'penghasilan_tidak_teratur_id' => $tidakRutin->id,
                                'id_tunjangan' => $tidakRutin->id_tunjangan,
                                'nominal' => $tidakRutin->nominal,
                                'updated_at' => $now,
                            ];
                            DB::table('batch_penghasilan_tidak_teratur')
                                ->where('gaji_per_bulan_id', $gaji->id)
                                ->where('penghasilan_tidak_teratur_id', $tidakRutin->id)
                                ->update($batch_tidak_rutin);
                        }
                        else {
                            // Insert
                            $batch_tidak_rutin = [
                                'gaji_per_bulan_id' => $gaji?->id,
                                'penghasilan_tidak_teratur_id' => $tidakRutin->id,
                                'id_tunjangan' => $tidakRutin->id_tunjangan,
                                'nominal' => $tidakRutin->nominal,
                                'created_at' => $now,
                            ];
                            DB::table('batch_penghasilan_tidak_teratur')
                                ->insert($batch_tidak_rutin);
                        }
                    }

                    // Get Batch Penghasilan Tidak Rutin
                    $batchPenghasilanTidakRutin = DB::table('batch_penghasilan_tidak_teratur AS p')
                                                    ->select(
                                                        'p.id',
                                                        'p.penghasilan_tidak_teratur_id',
                                                        'p.id_tunjangan',
                                                        'p.nominal',
                                                    )
                                                    ->where('p.gaji_per_bulan_id', $gaji?->id)
                                                    ->get();
                    foreach ($batchPenghasilanTidakRutin as $batchTidakRutin) {
                        $current = DB::table('penghasilan_tidak_teratur')
                                        ->where('id', $batchTidakRutin->penghasilan_tidak_teratur_id)
                                        ->first();
                        if (!$current) {
                            DB::table('batch_penghasilan_tidak_teratur')
                                ->where('id', $batchTidakRutin->id)
                                ->delete();
                        }
                    }

                    $total_pph = $bulan == 12 ? $this->getPPHBulanIni($bulan, $tahun, $item, $ptkp, $tanggal, $gaji_component) : HitungPPH::getPPh58($bulan, $tahun, $item, $ptkp, $tanggal, $total_gaji, $tunjangan_rutin);

                    // Hitung pajak intensif
                    $nominal_kredit = 0;
                    $nominal_penagihan = 0;
                    $pajak_kredit = 0;
                    $pajak_penagihan = 0;

                    if ($bulan > 1) {
                        // Penggajian bulan sebelumnya
                        $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                        $nominal_kredit = (int) DB::table('penghasilan_tidak_teratur')
                                                ->where('nip', $item->nip)
                                                ->where('id_tunjangan', 31) // kredit
                                                ->whereBetween('created_at', [$start_date, $tanggal])
                                                ->sum('nominal');
                        $nominal_penagihan = (int) DB::table('penghasilan_tidak_teratur')
                                                    ->where('nip', $item->nip)
                                                    ->where('id_tunjangan', 32) // penagihan
                                                    ->whereBetween('created_at', [$start_date, $tanggal])
                                                    ->sum('nominal');
                    }
                    else if ($bulan == 12) {
                        $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                        $currentMonth = intval(date('m', strtotime($tanggal)));
                        $currentYear = date('Y', strtotime($tanggal));
                        $last_day = getLastDateOfMonth($currentYear, $currentMonth);
                        $end_date = $currentYear.'-'.$currentMonth.'-'.$last_day;

                        $nominal_kredit = (int) DB::table('penghasilan_tidak_teratur')
                                                    ->where('nip', $item->nip)
                                                    ->where('id_tunjangan', 31) // kredit
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->sum('nominal');
                        $nominal_penagihan = (int) DB::table('penghasilan_tidak_teratur')
                                                    ->where('nip', $item->nip)
                                                    ->where('id_tunjangan', 32) // penagihan
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->sum('nominal');
                    }
                    else {
                        $nominal_kredit = (int) DB::table('penghasilan_tidak_teratur')
                                                ->where('nip', $item->nip)
                                                ->where('id_tunjangan', 31) // kredit
                                                ->where('tahun', (int) $tahun)
                                                ->where('bulan', (int) $bulan)
                                                ->whereDate('created_at', '<=', date('Y-m-d', strtotime($tanggal)))
                                                ->sum('nominal');
                        $nominal_penagihan = (int) DB::table('penghasilan_tidak_teratur')
                                                ->where('nip', $item->nip)
                                                ->where('id_tunjangan', 32) // penagihan
                                                ->where('tahun', (int) $tahun)
                                                ->where('bulan', (int) $bulan)
                                                ->whereDate('created_at', '<=', date('Y-m-d', strtotime($tanggal)))
                                                ->sum('nominal');
                    }

                    if ($nominal_kredit > 0) {
                        $pajak_kredit = HitungPPH::getPajakInsentif($nominal_kredit, 'kredit');
                    }
                    if ($nominal_penagihan > 0) {
                        $pajak_penagihan = HitungPPH::getPajakInsentif($nominal_penagihan, 'penagihan');
                    }

                    $pph = [
                        'gaji_per_bulan_id' => $gaji->id,
                        'nip' => $item->nip,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'total_pph' => $total_pph,
                        'insentif_kredit' => $pajak_kredit,
                        'insentif_penagihan' => $pajak_penagihan,
                        'updated_at' => $now
                    ];
                    PPHModel::where('gaji_per_bulan_id', $gaji->id)
                            ->where('nip', $item->nip)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->update($pph);
                }
                else {
                    $gaji = false;
                    // Validasi gaji di periode yang sama
                    $gaji = DB::table('gaji_per_bulan AS gaji')
                                ->select('gaji.*', 'batch_gaji_per_bulan.deleted_at')
                                ->join('batch_gaji_per_bulan', function($join) use ($kd_entitas, $bulan, $tahun) {
                                    $join->on('batch_gaji_per_bulan.id', 'gaji.batch_id')
                                        ->where('batch_gaji_per_bulan.kd_entitas', $kd_entitas)
                                        ->whereMonth('batch_gaji_per_bulan.tanggal_input', $bulan)
                                        ->whereMonth('batch_gaji_per_bulan.tanggal_input', $tahun)
                                        ->whereNull('batch_gaji_per_bulan.deleted_at');
                                })
                                ->where('gaji.nip', $item->nip)
                                ->where('gaji.bulan', $bulan)
                                ->where('gaji.tahun', $tahun)
                                ->where('gaji.batch_id', $batch_id)
                                ->whereNull('batch_gaji_per_bulan.deleted_at')
                                ->first();
                    if (!$gaji) {
                        $employee = [
                            'batch_id' => $batch_id,
                            'nip' => $item->nip,
                            'bulan' => $bulan,
                            'tahun' => $tahun,
                            'gj_pokok' => $item->gj_pokok,
                            'gj_penyesuaian' => $item->gj_penyesuaian,
                            'tj_keluarga' => $tunjangan[0],
                            'tj_telepon' => $tunjangan[1],
                            'tj_jabatan' => $tunjangan[2],
                            'tj_teller' => $tunjangan[3],
                            'tj_perumahan' => $tunjangan[4],
                            'tj_kemahalan' => $tunjangan[5],
                            'tj_pelaksana' => $tunjangan[6],
                            'tj_kesejahteraan' => $tunjangan[7],
                            'tj_multilevel' => $tunjangan[8],
                            'tj_ti' => $tunjangan[9],
                            'tj_transport' => $tunjangan[10],
                            'tj_pulsa' => $tunjangan[11],
                            'tj_vitamin' => $tunjangan[12],
                            'uang_makan' => $tunjangan[13],
                            'dpp' => $dpp,
                            'bpjs_tk' => $bpjs_tk,
                            'bpjs_tk_two' => $bpjs_tk_two,
                            'jkk' => $jkk,
                            'jht' => $jht,
                            'jkm' => $jkm,
                            'kesehatan' => $kesehatan,
                            'jp' => $jp_penambah,
                            'penambah_bruto_jamsostek' =>  $jamsostek,
                            'tj_fungsional' => $tunjangan[15],
                            'created_at' => $now,
                            'kredit_koperasi' => $kredit_koperasi,
                            'iuran_koperasi' => $iuran_koperasi,
                            'kredit_pegawai' => $kredit_pegawai,
                            'iuran_ik' => $iuran_ik,
                        ];
                        $gaji_id = GajiPerBulanModel::insertGetId($employee);
                        // Store Batch Penghasilan Tidak Rutin
                        foreach ($penghasilanTidakRutin as $tidakRutin) {
                            $batch_tidak_rutin = [
                                'gaji_per_bulan_id' => $gaji_id,
                                'penghasilan_tidak_teratur_id' => $tidakRutin->id,
                                'id_tunjangan' => $tidakRutin->id_tunjangan,
                                'nominal' => $tidakRutin->nominal,
                                'created_at' => $now,
                            ];
                            DB::table('batch_penghasilan_tidak_teratur')
                                ->insert($batch_tidak_rutin);
                        }

                        // Validasi gaji di periode yang sama
                        $pph_bulan_ini = DB::table('pph_yang_dilunasi AS pph')
                                            ->join('gaji_per_bulan AS gaji', 'gaji.id', 'pph.gaji_per_bulan_id')
                                            ->join('batch_gaji_per_bulan', function($join) use ($kd_entitas, $bulan, $tahun) {
                                                $join->on('batch_gaji_per_bulan.id', 'gaji.batch_id')
                                                    ->where('batch_gaji_per_bulan.kd_entitas', $kd_entitas)
                                                    ->whereMonth('batch_gaji_per_bulan.tanggal_input', $bulan)
                                                    ->whereMonth('batch_gaji_per_bulan.tanggal_input', $tahun)
                                                    ->whereNull('batch_gaji_per_bulan.deleted_at');
                                            })
                                            ->where('pph.nip', $item->nip)
                                            ->where('pph.bulan', $bulan)
                                            ->where('pph.tahun', $tahun)
                                            ->first();
                        if (!$pph_bulan_ini) {
                            $total_pph = $bulan == 12 ? $this->getPPHBulanIni($bulan, $tahun, $item, $ptkp, $tanggal, $gaji_component) : HitungPPH::getPPh58($bulan, $tahun, $item, $ptkp, $tanggal, $total_gaji, $tunjangan_rutin);

                            // Hitung pajak intensif
                            $nominal_kredit = 0;
                            $nominal_penagihan = 0;
                            $pajak_kredit = 0;
                            $pajak_penagihan = 0;

                            if ($bulan > 1) {
                                // Penggajian bulan sebelumnya
                                $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                $nominal_kredit = (int) DB::table('penghasilan_tidak_teratur')
                                                        ->where('nip', $item->nip)
                                                        ->where('id_tunjangan', 31) // kredit
                                                        ->whereBetween('created_at', [$start_date, $tanggal])
                                                        ->sum('nominal');
                                $nominal_penagihan = (int) DB::table('penghasilan_tidak_teratur')
                                                            ->where('nip', $item->nip)
                                                            ->where('id_tunjangan', 32) // penagihan
                                                            ->whereBetween('created_at', [$start_date, $tanggal])
                                                            ->sum('nominal');
                            }
                            else if ($bulan == 12) {
                                $start_date = HitungPPH::getDatePenggajianSebelumnya($tanggal, $kd_entitas);
                                $currentMonth = intval(date('m', strtotime($tanggal)));
                                $currentYear = date('Y', strtotime($tanggal));
                                $last_day = getLastDateOfMonth($currentYear, $currentMonth);
                                $end_date = $currentYear.'-'.$currentMonth.'-'.$last_day;

                                $nominal_kredit = (int) DB::table('penghasilan_tidak_teratur')
                                                            ->where('nip', $item->nip)
                                                            ->where('id_tunjangan', 31) // kredit
                                                            ->whereBetween('created_at', [$start_date, $end_date])
                                                            ->sum('nominal');
                                $nominal_penagihan = (int) DB::table('penghasilan_tidak_teratur')
                                                            ->where('nip', $item->nip)
                                                            ->where('id_tunjangan', 32) // penagihan
                                                            ->whereBetween('created_at', [$start_date, $end_date])
                                                            ->sum('nominal');
                            }
                            else {
                                $nominal_kredit = (int) DB::table('penghasilan_tidak_teratur')
                                                        ->where('nip', $item->nip)
                                                        ->where('id_tunjangan', 31) // kredit
                                                        ->where('tahun', (int) $tahun)
                                                        ->where('bulan', (int) $bulan)
                                                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($tanggal)))
                                                        ->sum('nominal');
                                $nominal_penagihan = (int) DB::table('penghasilan_tidak_teratur')
                                                        ->where('nip', $item->nip)
                                                        ->where('id_tunjangan', 32) // penagihan
                                                        ->where('tahun', (int) $tahun)
                                                        ->where('bulan', (int) $bulan)
                                                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($tanggal)))
                                                        ->sum('nominal');
                            }

                            if ($nominal_kredit > 0) {
                                $pajak_kredit = HitungPPH::getPajakInsentif($nominal_kredit, 'kredit');
                            }
                            if ($nominal_penagihan > 0) {
                                $pajak_penagihan = HitungPPH::getPajakInsentif($nominal_penagihan, 'penagihan');
                            }

                            $pph = [
                                'gaji_per_bulan_id' => $gaji_id,
                                'nip' => $item->nip,
                                'bulan' => $bulan,
                                'tahun' => $tahun,
                                'total_pph' => $total_pph,
                                'insentif_kredit' => $pajak_kredit,
                                'insentif_penagihan' => $pajak_penagihan,
                                'tanggal' => now(),
                                'created_at' => now()
                            ];
                            PPHModel::insert($pph);
                        }
                        else {
                            DB::rollBack();
                            Alert::warning('Peringatan', 'Telah melakukan proses penggajian pada periode ini.');
                            return redirect()->route('gaji_perbulan.index');
                        }
                    }
                    else {
                        DB::rollBack();
                        Alert::warning('Peringatan', 'Telah melakukan proses penggajian pada periode ini.');
                        return redirect()->route('gaji_perbulan.index');
                    }
                }
            }

            DB::commit();
            if ($request->has('batch_id')) {
                Alert::success('Berhasil', 'Berhasil memperbarui penggajian karyawan.');
            }
            else {
                Alert::success('Berhasil', 'Berhasil melakukan proses penggajian karyawan.');
            }

            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('gaji_perbulan.index');
        } catch (QueryException $e) {
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('gaji_perbulan.index');
        }
    }

    public function prosesFinal(Request $request) {
        DB::beginTransaction();
        try {
            $batch = DB::table('batch_gaji_per_bulan')
                        ->where('id', $request->batch_id)
                        ->first();
            $prev = null;
            if ($batch) {
                $prev = DB::table('batch_gaji_per_bulan')
                            ->where('tanggal_input', '<', $batch->tanggal_input)
                            ->where('kd_entitas', $batch->kd_entitas)
                            ->where('status', 'proses')
                            ->whereNull('deleted_at')
                            ->orderByDesc('tanggal_input')
                            ->first();

                if ($prev) {
                    Alert::error('Gagal', 'Harap lakukan final proses pada penghasilan yang sebelumnya terlebih dahulu');
                    return back();
                }
                else {
                    DB::table('batch_gaji_per_bulan')
                        ->where('id', $request->batch_id)
                        ->update([
                            'status' => 'final',
                            'tanggal_final' => date('Y-m-d'),
                            'updated_at' => now(),
                        ]);
                }
            }

            DB::commit();
            Alert::success('Berhasil', 'Berhasil memproses penghasilan.');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return back();
        }
    }

    function getPPHBulanIni($bulan, $tahun, $karyawan, $ptkp, $tanggal, GajiComponent $gaji_component)
    {
        $pph = 0;
        if (intval($bulan) > 1) {
            $tunjangan = array();
            $tunjanganJamsostek = array();
            $pengurang = array();
            $totalGaji = array();
            $totalGajiJamsostek = array();
            $penambah = array();
            $tunjanganBulanIni = 0;
            $tjJamsostekBulanIni = 0;
            $totalGajiBulanIni = 0;
            $tKeluarga = 0;
            $tKesejahteraan = 0;

            $tjBulanIni = DB::table('tunjangan_karyawan')
                            ->select('tunjangan_karyawan.*', 'm.kategori', 'm.status')
                            ->join('mst_tunjangan AS m', 'm.id', 'tunjangan_karyawan.id_tunjangan')
                            ->where('nip', $karyawan->nip)
                            ->where(function($query) {
                                $query->where('m.kategori', 'teratur')
                                    ->orWhereNull('m.kategori')
                                    ->where('status', 1);
                            })
                            ->get();

            foreach ($tjBulanIni as $key => $value) {
                $tunjanganBulanIni += $value->nominal;
                if ($value->id_tunjangan == 1) $tKeluarga += $value->nominal;
                if ($value->id_tunjangan == 8) $tKesejahteraan += $value->nominal;
                if ($value->status == 1) $tjJamsostekBulanIni += $value->nominal;
            }

            $penghasilanTidakTeraturBulanIni = DB::table('penghasilan_tidak_teratur')
                                                    ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
                                                    ->where('m.kategori', 'tidak teratur')
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('bulan', intval($bulan))
                                                    ->where('tahun', intval($tahun))
                                                    ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
                                                    ->sum('nominal');
            $dataGaji = DB::table('gaji_per_bulan')
                            ->where('nip', $karyawan->nip)
                            ->where('tahun', $tahun)
                            ->where('bulan', '<', intval($bulan))
                            ->get();
            $bonusBulanIni = DB::table('penghasilan_tidak_teratur')
                                ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
                                ->where('m.kategori', 'bonus')
                                ->where('nip', $karyawan->nip)
                                ->where('tahun', intval($tahun))
                                ->where('bulan', intval($bulan))
                                ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
                                ->sum('nominal');

            // Bonus bulan sebelumnya
            $bonus = DB::table('penghasilan_tidak_teratur')
                ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
                ->where('m.kategori', 'bonus')
                ->where('nip', $karyawan->nip)
                ->where('tahun', intval($tahun))
                ->where('bulan', '<', intval($bulan))
                ->sum('nominal');

            foreach ($dataGaji as $key => $gaji) {
                $this->param['nominalJp'] = ($key < 2) ? $this->param['jpJanFeb'] : $this->param['jpMarDes'];
                unset($tunjangan);
                unset($tunjanganJamsostek);
                $tunjangan = array();
                $tunjanganJamsostek = array();
                $penghasilanTidakTeratur = DB::table('penghasilan_tidak_teratur')
                                                ->where('nip', $karyawan->nip)
                                                ->where('tahun', $tahun)
                                                ->where('bulan', $key + 1)
                                                ->sum('nominal');

                foreach ($this->param['namaTunjangan'] as $keyTunjangan => $item) {
                    array_push($tunjangan, $gaji->$item);
                    if ($keyTunjangan < 11)
                        array_push($tunjanganJamsostek, $gaji->$item);
                }

                $totalGj = $gaji->gj_pokok + $gaji->gj_penyesuaian;
                $totalGjJamsotek = $totalGj + array_sum($tunjanganJamsostek);
                $penambah_jamsostek = $gaji_component->getPenambahBrutoJamsostek($karyawan->kpj, $karyawan->jkn, $totalGjJamsotek);
                $totalGj += $penghasilanTidakTeratur + array_sum($tunjangan) + $penambah_jamsostek;
                array_push($pengurang, $gaji_component->getDPP($karyawan->status_karyawan, $gaji->gj_pokok, $gaji->tj_keluarga, $gaji->tj_kesejahteraan));
                array_push($totalGaji, $totalGj);
                array_push($totalGajiJamsostek, $totalGjJamsotek);
                array_push($penambah, $penambah_jamsostek);
            }
            $totalGajiBulanIni = $karyawan->gj_pokok + $karyawan->gj_penyesuaian;
            $totalGjJamsostekBulanIni = $totalGajiBulanIni + $tjJamsostekBulanIni;
            $penambah_jamsostek_bln_ini = $gaji_component->getPenambahBrutoJamsostek($karyawan->kpj, $karyawan->jkn, $totalGjJamsostekBulanIni);
            $totalGajiBulanIni += $penghasilanTidakTeraturBulanIni + $tunjanganBulanIni  + $penambah_jamsostek_bln_ini;
            $bonus += $bonusBulanIni;

            array_push($pengurang, $gaji_component->getDPP($karyawan->status_karyawan, $karyawan->gj_pokok, $tKeluarga, $tKesejahteraan));
            array_push($totalGaji, $totalGajiBulanIni);
            array_push($totalGajiJamsostek, $totalGjJamsostekBulanIni);
            array_push($penambah, $penambah_jamsostek_bln_ini);
        } else {
            $this->param['nominalJp'] = ($bulan <= 2) ? $this->param['jpJanFeb'] : $this->param['jpMarDes'];
            $tunjangan = array();
            $tunjanganJamsostek = array();
            $pengurang = array();
            $totalGaji = array();
            $totalGajiJamsostek = array();
            $penambah = array();
            $tunjanganBulanIni = 0;
            $tjJamsostekBulanIni = 0;
            $totalGajiBulanIni = 0;
            $tKeluarga = 0;
            $tKesejahteraan = 0;

            $tjBulanIni = DB::table('tunjangan_karyawan')
                            ->select('tunjangan_karyawan.*', 'm.kategori', 'm.status')
                            ->join('mst_tunjangan AS m', 'm.id', 'tunjangan_karyawan.id_tunjangan')
                            ->where('nip', $karyawan->nip)
                            ->where(function($query) {
                                $query->where('m.kategori', 'teratur')
                                    ->orWhereNull('m.kategori')
                                    ->where('status', 1);
                            })
                            ->get();

            foreach ($tjBulanIni as $key => $value) {
                $tunjanganBulanIni += $value->nominal;
                if ($value->id_tunjangan == 1) $tKeluarga += $value->nominal;
                if ($value->id_tunjangan == 8) $tKesejahteraan += $value->nominal;
                if ($value->status == 1) $tjJamsostekBulanIni += $value->nominal;
            }

            $penghasilanTidakTeraturBulanIni = DB::table('penghasilan_tidak_teratur')
                                                    ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
                                                    ->where('m.kategori', 'tidak teratur')
                                                    ->where('nip', $karyawan->nip)
                                                    ->where('bulan', $bulan)
                                                    ->where('tahun', $tahun)
                                                    ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
                                                    ->sum('penghasilan_tidak_teratur.nominal');
            $bonus = DB::table('penghasilan_tidak_teratur')
                        ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
                        ->where('m.kategori', 'bonus')
                        ->where('nip', $karyawan->nip)
                        ->where('tahun', intval($tahun))
                        ->where('bulan', intval($bulan))
                        ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
                        ->sum('penghasilan_tidak_teratur.nominal');
            $totalGajiBulanIni = $karyawan->gj_pokok + $karyawan->gj_penyesuaian;
            $totalGjJamsostekBulanIni = $totalGajiBulanIni + $tjJamsostekBulanIni;
            $penambah_jamsostek_bln_ini = $gaji_component->getPenambahBrutoJamsostek($karyawan->kpj, $karyawan->jkn, $totalGjJamsostekBulanIni);
            $totalGajiBulanIni += $penghasilanTidakTeraturBulanIni + $tunjanganBulanIni + $penambah_jamsostek_bln_ini;

            array_push($pengurang, $gaji_component->getDPP($karyawan->status_karyawan, $karyawan->gj_pokok, $tKeluarga, $tKesejahteraan));
            array_push($totalGaji, $totalGajiBulanIni);
            array_push($totalGajiJamsostek, $totalGjJamsostekBulanIni);
            array_push($penambah, $penambah_jamsostek_bln_ini);
        }

        $lima_persen = floor(0.05 * array_sum($totalGaji));
        $keterangan = 500000 * intval($bulan);
        $biaya_jabatan = 0;
        if ($lima_persen > $keterangan) {
            $biaya_jabatan = $keterangan;
        } else {
            $biaya_jabatan = $lima_persen;
        }
        $rumus_14 = 0;
        if (0.05 * (array_sum($totalGaji)) > $keterangan) {
            $rumus_14 = floor($keterangan);
        } else {
            $rumus_14 = floor(0.05 * (array_sum($totalGaji)));
        }
        $no_14 = ((array_sum($totalGaji) - $bonus - array_sum($pengurang) - $biaya_jabatan) / intval($bulan) * 12 + $bonus + ($biaya_jabatan - $rumus_14));
        $no_14 = floor($no_14);
        $persen5 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 0) {
            if (($no_14 - $ptkp?->ptkp_tahun) <= 60000000) {
                $persen5 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.05 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.06;
            } else {
                $persen5 = ($karyawan->npwp != null) ? 60000000 * 0.05 : 60000000 * 0.06;
            }
        } else {
            $persen5 = 0;
        }
        $persen5 = floor($persen5);
        $persen15 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 60000000) {
            if (floor($no_14 - $ptkp?->ptkp_tahun) <= 250000000) {
                $persen15 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.15 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.18;
            } else {
                $persen15 = 190000000 * 0.15;
            }
        } else {
            $persen15 = 0;
        }
        $persen15 = floor($persen15);
        $persen25 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 250000000) {
            if (($no_14 - $ptkp?->ptkp_tahun) <= 500000000) {
                $persen25 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.25 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.3;
            } else {
                $persen25 = 250000000 * 0.25;
            }
        } else {
            $persen25 = 0;
        }
        $persen25 = floor($persen25);
        $persen30 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 500000000) {
            if (($no_14 - $ptkp?->ptkp_tahun) <= 5000000000) {
                $persen30 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.3 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.36;
            } else {
                $persen30 = 4500000000 * 0.30;
            }
        } else {
            $persen30 = 0;
        }
        $persen30 = floor($persen30);
        $persen35 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 5000000000) {
            $persen35 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.35 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.42;
        } else {
            $persen35 = 0;
        }
        $persen35 = floor($persen35);
        $no17 = (($persen5 + $persen15 + $persen25 + $persen30 + $persen35) / 1000) * 1000;
        $no17 = floor($no17);

        $pph = floor(($no17 / 12) * intval($bulan));
        if (intval($bulan) > 1) {
            $pphTerbayar = (int) DB::table('pph_yang_dilunasi')
                                    ->where('nip', $karyawan->nip)
                                    ->where('tahun', $tahun)
                                    ->sum('total_pph');
            $pph -= $pphTerbayar;
        }
        return floor($pph);
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

    public function importPPH(Request $request)
    {
        // Need permission
        $file = $request->file('upload_csv');
        $import = new ImportPPH21;
        $import = $import->import($file);

        Alert::success('Berhasil', 'Berhasil mengimport data excel');
        return redirect()->route('karyawan.index');
    }

    public function getPPHDesember($bulan, $tahun, $karyawan, $ptkp, GajiComponent $gaji_component){
        $tanggal = date('Y-m-d', strtotime(Carbon::createFromFormat('Y-m-d', date('Y') . '-' . $bulan . '-' . '26')));
        $pph = 0;
        $tunjangan = array();
        $tunjanganJamsostek = array();
        $pengurang = array();
        $totalGaji = array();
        $totalGajiJamsostek = array();
        $penambah = array();
        $tunjanganBulanIni = 0;
        $tjJamsostekBulanIni = 0;
        $totalGajiBulanIni = 0;
        $tKeluarga = 0;
        $tKesejahteraan = 0;

        $tjBulanIni = DB::table('tunjangan_karyawan')
                        ->select('tunjangan_karyawan.*', 'm.kategori', 'm.status')
                        ->join('mst_tunjangan AS m', 'm.id', 'tunjangan_karyawan.id_tunjangan')
                        ->where('nip', $karyawan->nip)
                        ->where(function($query) {
                            $query->where('m.kategori', 'teratur')
                                ->orWhereNull('m.kategori')
                                ->where('status', 1);
                        })
                        ->get();

        foreach ($tjBulanIni as $key => $value) {
            $tunjanganBulanIni += $value->nominal;
            if ($value->id_tunjangan == 1) $tKeluarga += $value->nominal;
            if ($value->id_tunjangan == 8) $tKesejahteraan += $value->nominal;
            if ($value->status == 1) $tjJamsostekBulanIni += $value->nominal;
        }

        $dataGaji = DB::table('gaji_per_bulan')
            ->where('nip', $karyawan->nip)
            ->where('tahun', $tahun)
            ->get();

        // Bonus bulan sebelumnya
        $bonus = DB::table('penghasilan_tidak_teratur')
            ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
            ->where('m.kategori', 'bonus')
            ->where('nip', $karyawan->nip)
            ->where('tahun', intval($tahun))
            ->sum('nominal');

        foreach ($dataGaji as $key => $gaji) {
            $this->param['nominalJp'] = ($key < 2) ? $this->param['jpJanFeb'] : $this->param['jpMarDes'];
            unset($tunjangan);
            unset($tunjanganJamsostek);
            $tunjangan = array();
            $tunjanganJamsostek = array();
            $penghasilanTidakTeratur = DB::table('penghasilan_tidak_teratur')
                ->where('nip', $karyawan->nip)
                ->where('tahun', $tahun)
                ->where('bulan', $key + 1)
                ->sum('nominal');

            foreach ($this->param['namaTunjangan'] as $keyTunjangan => $item) {
                array_push($tunjangan, $gaji->$item);
                if ($keyTunjangan < 11)
                    array_push($tunjanganJamsostek, $gaji->$item);
            }

            $totalGj = $gaji->gj_pokok + $gaji->gj_penyesuaian;
            $totalGjJamsotek = $totalGj + array_sum($tunjanganJamsostek);
            $penambah_jamsostek = $gaji_component->getPenambahBrutoJamsostek($karyawan->kpj, $karyawan->jkn, $totalGjJamsotek);
            $totalGj += $penghasilanTidakTeratur + array_sum($tunjangan) + $penambah_jamsostek;
            array_push($pengurang, $gaji_component->getDPP($karyawan->status_karyawan, $gaji->gj_pokok, $gaji->tj_keluarga, $gaji->tj_kesejahteraan));
            array_push($totalGaji, $totalGj);
            array_push($totalGajiJamsostek, $totalGjJamsotek);
            array_push($penambah, $penambah_jamsostek);
        }

        $lima_persen = floor(0.05 * array_sum($totalGaji));
        $keterangan = 500000 * intval($bulan);
        $biaya_jabatan = 0;
        if ($lima_persen > $keterangan) {
            $biaya_jabatan = $keterangan;
        } else {
            $biaya_jabatan = $lima_persen;
        }
        $rumus_14 = 0;
        if (0.05 * (array_sum($totalGaji)) > $keterangan) {
            $rumus_14 = floor($keterangan);
        } else {
            $rumus_14 = floor(0.05 * (array_sum($totalGaji)));
        }
        $no_14 = floor((array_sum($totalGaji) - $bonus - array_sum($pengurang) - $biaya_jabatan) / intval($bulan) * 12 + $bonus + ($biaya_jabatan - $rumus_14));

        $persen5 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 0) {
            if (($no_14 - $ptkp?->ptkp_tahun) <= 60000000) {
                $persen5 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.05 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.06;
            } else {
                $persen5 = ($karyawan->npwp != null) ? 60000000 * 0.05 : 60000000 * 0.06;
            }
        } else {
            $persen5 = 0;
        }
        $persen15 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 60000000) {
            if (($no_14 - $ptkp?->ptkp_tahun) <= 250000000) {
                $persen15 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.15 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.18;
            } else {
                $persen15 = 190000000 * 0.15;
            }
        } else {
            $persen15 = 0;
        }
        $persen25 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 250000000) {
            if (($no_14 - $ptkp?->ptkp_tahun) <= 500000000) {
                $persen25 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.25 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.3;
            } else {
                $persen25 = 250000000 * 0.25;
            }
        } else {
            $persen25 = 0;
        }
        $persen30 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 500000000) {
            if (($no_14 - $ptkp?->ptkp_tahun) <= 5000000000) {
                $persen30 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.3 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.36;
            } else {
                $persen30 = 4500000000 * 0.30;
            }
        } else {
            $persen30 = 0;
        }
        $persen35 = 0;
        if (($no_14 - $ptkp?->ptkp_tahun) > 5000000000) {
            $persen35 = ($karyawan->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.35 : (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 5000000000) * 0.42;
        } else {
            $persen35 = 0;
        }

        $no17 = (($persen5 + $persen15 + $persen25 + $persen30 + $persen35) / 1000) * 1000;

        $pph = floor(($no17 / 12) * intval($bulan));
        $pphTerbayar = DB::table('pph_yang_dilunasi')
            ->where('nip', $karyawan->nip)
            ->where('tahun', $tahun)
            ->sum('total_pph');
        $pph -= $pphTerbayar;
        $pphDesember = DB::table('pph_yang_dilunasi')
            ->where('nip', $karyawan->nip)
            ->where('tahun', $tahun)
            ->where('bulan', 12)
            ->first()?->total_pph;

        return $pph + $pphDesember;
    }

    public function storePPHDesember($nip, $tahun, $bulan, $gaji_component){
        $cabang = array();
        $tunjangan = array();
        $tjJamsostek = array();
        $cbg = DB::table('mst_cabang')
            ->select('kd_cabang')
            ->get();
        foreach ($cbg as $item) {
            array_push($cabang, $item->kd_cabang);
        }

        $karyawan = DB::table('mst_karyawan')
                    ->where('nip', $nip)
                    ->get();

        // Get Penghasilan from mst_karyawan + tunjangan karyawan + penghasilan tidak teratur
        $item_penghasilan_teratur = TunjanganModel::select('id','nama_tunjangan', 'kategori', 'status')
            ->where('kategori', 'teratur')
            ->orWhereNull('kategori')
            ->orderBy('id')
            ->get();

        foreach ($karyawan as $key => $item) {
            unset($tunjangan);
            unset($tjJamsostek);
            $tjJamsostek = array();
            $tunjangan = array();

            // Get tunjangan karyawan
            foreach ($item_penghasilan_teratur as $tunj) {
                if ($tunj->status == 1 || $tunj->kategori == null) {
                    // GET Tunjangan (THP)
                    $tj = DB::table('tunjangan_karyawan')
                        ->where('nip', $item->nip)
                        ->where('id_tunjangan', $tunj->id)
                        ->first();
                    array_push($tunjangan, ($tj != null) ? $tj->nominal : 0);
                    if ($tunj->status) {
                        array_push($tjJamsostek, ($tj != null) ? $tj->nominal : 0);
                    }
                }
                else {
                    // GET Transaksi Tunjangan
                    $tj = DB::table('transaksi_tunjangan')
                        ->where('nip', $item->nip)
                        ->where('id_tunjangan', $tunj->id)
                        ->where('tahun', $tahun)
                        ->where('bulan', $bulan)
                        ->first();
                    array_push($tunjangan, ($tj != null) ? $tj->nominal : 0);
                    if ($tunj->status) {
                        array_push($tjJamsostek, ($tj != null) ? $tj->nominal : 0);
                    }
                }
            }

            $ptkp = HitungPPH::getPTKP($item);

            // Get penambah & pengurang bruto
            if (in_array($item->kd_entitas, $cabang)) {
                $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                    ->where('kd_cabang', $item->kd_entitas)
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
            } else {
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
            $this->param['persenJkk'] = $hitungan_penambah->jkk;
            $this->param['persenJht'] = $hitungan_penambah->jht;
            $this->param['persenJkm'] = $hitungan_penambah->jkm;
            $this->param['persenKesehatan'] = $hitungan_penambah->kesehatan;
            $this->param['persenJpPenambah'] = $hitungan_penambah->jp;
            $this->param['persenDpp'] = $hitungan_pengurang->dpp;
            $this->param['persenJpPengurang'] = $hitungan_pengurang->jp;
            $this->param['batasAtas'] = $hitungan_penambah->kesehatan_batas_atas;
            $this->param['batasBawah'] = $hitungan_penambah->kesehatan_batas_bawah;
            $this->param['jpJanFeb'] = $hitungan_pengurang->jp_jan_feb;
            $this->param['jpMarDes'] = $hitungan_pengurang->jp_mar_des;
            $this->param['nominalJp'] = 0;
        }

        return intval($this->getPPHDesember($bulan, $tahun, $item, $ptkp, $gaji_component));
    }

    public function getRincianPayroll(Request $request) {
        $is_cabang = auth()->user()->hasRole('cabang');
        $kantor = $is_cabang ? auth()->user()->kd_cabang : 'pusat';
        $batch_id = $request->batch_id;
        $cetak = $request->cetak ?? null;
        $data_batch = GajiPerBulanModel::where('batch_id', $batch_id)->select('bulan', 'tahun')->first();
        $bulan = $data_batch->bulan;
        $tahun = $data_batch->tahun;

        $payrollRepo = new PayrollRepository;
        $data = $payrollRepo->getJson($kantor, $bulan, $tahun, $cetak, $batch_id);

        return DataTables::of($data)
                        ->addColumn('counter', function ($row) {
                            static $count = 0;
                            $count++;
                            return $count;
                        })
                        ->rawColumns(['counter','grand_total'])
                        ->make(true);
    }

    public function getLampiranGaji($id){
        $data = DB::table('batch_gaji_per_bulan AS batch')
        ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
        ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
        ->select(
            'batch.id',
            'batch.tanggal_input',
            'batch.tanggal_final',
            'batch.status',
            'gaji.bulan',
            'gaji.tahun',
        )->where('batch.id',$id)->first();
        $year = date('Y',strtotime($data->tanggal_input));
        $month = str_replace('0','',date('m',strtotime($data->tanggal_input)));
        $kantor = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : 'pusat';
        $cetak = new CetakGajiRepository;
        $result = $cetak->cetak($kantor, $month, $year,$id);

        return DataTables::of($result)
                        ->addColumn('counter', function ($row) {
                            static $count = 0;
                            $count++;
                            return $count;
                        })
                        ->rawColumns(['counter'])
                        ->make(true);
    }

    public function cetak($id) {
        $data = DB::table('batch_gaji_per_bulan AS batch')
            ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
            ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
            ->select(
                'batch.id',
                'batch.tanggal_input',
                'batch.tanggal_final',
                'batch.status',
                'gaji.bulan',
                'gaji.tahun',
            )
            ->where('batch.id',$id)
            ->first();
        $year = date('Y',strtotime($data->tanggal_input));
        $month = str_replace('0','',date('m',strtotime($data->tanggal_input)));
        $kantor = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : 'pusat';
        $cetak = new CetakGajiRepository;
        $result = $cetak->cetak($kantor, $month, $year,$id);

        if (auth()->user()->hasRole('cabang')) {
            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
            // Get last penggajian
            $last_date_penggajian = GajiPerBulanRepository::getLastPenggajianCurrentYear($kd_entitas, true);
            $last_month_penggajian = 0;
            if ($last_date_penggajian) {
                $last_month_penggajian = intval(date('m', strtotime($last_date_penggajian->tanggal_input)));
            }
            $cabang = DB::table('mst_cabang')->select('kd_cabang', 'nama_cabang')->where('kd_cabang', $kd_entitas)->first();
            $ttdKaryawan = KaryawanModel::select(
                        'mst_karyawan.nip',
                        'mst_karyawan.nik',
                        'mst_karyawan.nama_karyawan',
                        'mst_karyawan.kd_bagian',
                        'mst_karyawan.kd_jabatan',
                        'mst_karyawan.kd_entitas',
                        'mst_karyawan.tanggal_penonaktifan',
                        'mst_karyawan.status_jabatan',
                        'mst_karyawan.ket_jabatan',
                        'mst_karyawan.kd_entitas',
                        DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang) LIMIT 1), 1, 0) AS status_kantor")
                    )
                    ->with('jabatan')
                    ->with('bagian')
                    ->where('kd_entitas',$kd_entitas)
                    ->whereNotIn('kd_jabatan',['ST','NST'])
                    ->whereRaw("(tanggal_penonaktifan IS NULL OR ($last_month_penggajian = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                    ->orderByRaw($this->orderRaw)
                    ->orderBy('mst_karyawan.kd_entitas')
                    ->get()
                    ->reverse();

            foreach ($ttdKaryawan as $key => $krywn) {
                $krywn->prefix = match($krywn->status_jabatan) {
                    'Penjabat' => 'Pj. ',
                    'Penjabat Sementara' => 'Pjs. ',
                    default => '',
                };

                $jabatan = $krywn->jabatan->nama_jabatan;

                $krywn->ket = $krywn->ket_jabatan ? "({$krywn->ket_jabatan})" : "";

                if(isset($krywn->entitas->subDiv)) {
                    $krywn->entitas_result = $krywn->entitas->subDiv->nama_subdivisi;
                } else if(isset($krywn->entitas->div)) {
                    $krywn->entitas_result = $krywn->entitas->div->nama_divisi;
                } else {
                    $krywn->entitas_result = '';
                }

                if ($jabatan == "Pemimpin Sub Divisi") {
                    $jabatan = 'PSD';
                } else if ($jabatan == "Pemimpin Bidang Operasional") {
                    $jabatan = 'PBO';
                } else if ($jabatan == "Pemimpin Bidang Pemasaran") {
                    $jabatan = 'PBP';
                } else {
                    $jabatan = $krywn->jabatan->nama_jabatan;
                }

                $krywn->jabatan_result = $jabatan;
            }

        } else{
            $kd_entitas = '000';
            // Get last penggajian
            $last_date_penggajian = GajiPerBulanRepository::getLastPenggajianCurrentYear($kd_entitas);
            $last_month_penggajian = 0;
            if ($last_date_penggajian) {
                $last_month_penggajian = intval(date('m', strtotime($last_date_penggajian->tanggal_input)));
            }
            $cabang = null;
            $ttdKaryawan = KaryawanModel::select(
                                'mst_karyawan.nip',
                                'mst_karyawan.nik',
                                'mst_karyawan.nama_karyawan',
                                'mst_karyawan.kd_bagian',
                                'mst_karyawan.kd_jabatan',
                                'mst_karyawan.kd_entitas',
                                'mst_karyawan.tanggal_penonaktifan',
                                'mst_karyawan.status_jabatan',
                                'mst_karyawan.ket_jabatan',
                                'mst_karyawan.kd_entitas',
                                DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang) LIMIT 1), 1, 0) AS status_kantor")
                            )
                            ->with('jabatan')
                            ->with('bagian')
                            ->where('kd_jabatan', 'PIMDIV')
                            ->where('kd_entitas', 'UMUM')
                            ->whereRaw("(tanggal_penonaktifan IS NULL OR ($last_month_penggajian = MONTH(tanggal_penonaktifan) AND is_proses_gaji = 1))")
                            ->first();
            if ($ttdKaryawan) {
                $ttdKaryawan->prefix = match($ttdKaryawan->status_jabatan) {
                    'Penjabat' => 'Pj. ',
                    'Penjabat Sementara' => 'Pjs. ',
                    default => '',
                };

                $jabatan = $ttdKaryawan->jabatan->nama_jabatan;

                $ttdKaryawan->ket = $ttdKaryawan->ket_jabatan ? "({$ttdKaryawan->ket_jabatan})" : "";

                if(isset($ttdKaryawan->entitas->subDiv)) {
                    $ttdKaryawan->entitas_result = $ttdKaryawan->entitas->subDiv->nama_subdivisi;
                } else if(isset($ttdKaryawan->entitas->div)) {
                    $ttdKaryawan->entitas_result = $ttdKaryawan->entitas->div->nama_divisi;
                } else {
                    $ttdKaryawan->entitas_result = '';
                }

                if ($jabatan == "Pemimpin Sub Divisi") {
                    $jabatan = 'PSD';
                } else if ($jabatan == "Pemimpin Bidang Operasional") {
                    $jabatan = 'PBO';
                } else if ($jabatan == "Pemimpin Bidang Pemasaran") {
                    $jabatan = 'PBP';
                } else {
                    $jabatan = $ttdKaryawan->jabatan->nama_jabatan;
                }

                $ttdKaryawan->jabatan_result = $jabatan;
            }
        }

        $namaBulan = [
            "01" => "Januari",
            "02" => "Februari",
            "03" => "Maret",
            "04" => "April",
            "05" => "Mei",
            "06" => "Juni",
            "07" => "July",
            "08" => "Agustus",
            "09" => "Septemper",
            "10" => "Oktober",
            "11" => "November",
            "12" => "Desember"
        ];
        $tanggalSekarang = date('d', strtotime($data->tanggal_input));
        $bulanSekarang = $namaBulan[date('m')];
        $tahunSekarang = date('Y', strtotime($data->tanggal_input));
        $tanggal = $tanggalSekarang . ' ' . $bulanSekarang . ' ' . $tahunSekarang;

        return view('gaji_perbulan.cetak-pdf',['data' => $result,'month' => $month, 'year' => $year,'tanggal' => $tanggal,'ttdKaryawan' => $ttdKaryawan,'cabang' => $cabang]);
    }

    public function updateTanggalCetak($id) {
        $status = '';
        $message = '';

        try {
            $kd_entitas = auth()->user()->kd_cabang;
            $batch = DB::table('batch_gaji_per_bulan')->where('id',$id)->first();
            if (auth()->user()->can('penghasilan - proses penghasilan - proses')) {
                if ($batch) {
                    if (!$kd_entitas) {
                        if ($batch->kd_entitas == '000') {
                            if (!$batch->tanggal_cetak) {
                                $now = Carbon::now();
                                DB::table('batch_gaji_per_bulan')->where('id',$id)->update([
                                    'tanggal_cetak' => $now,
                                    'updated_at' => $now,
                                ]);
                                $status = 'success';
                                $message = 'Berhasil memperbarui tanggal cetak';
                            }
                        }
                    }
                    else if ($batch->kd_entitas == $kd_entitas) {
                        if (!$batch->tanggal_cetak) {
                            $now = Carbon::now();
                            DB::table('batch_gaji_per_bulan')->where('id',$id)->update([
                                'tanggal_cetak' => $now,
                                'updated_at' => $now,
                            ]);
                            $status = 'success';
                            $message = 'Berhasil memperbarui tanggal cetak';
                        }
                    }
                }
            }

            $status = 'failed';
            $message = 'Gagal memperbarui tanggal cetak';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = $e->getMessage();
        } finally {
            $response = [
                'status' => $status,
                'message' => $message,
                'batch' => $batch,
            ];

            return response()->json($response);

        }
    }

    function upload(Request $request){
        $request->validate([
            'upload_file' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $folderLampiran = public_path() . '/upload/' . $request->id . '/';
            $file = $request->upload_file;
            $filenameLampiran = $file->getClientOriginalName();
            $pathSPPK = realpath($folderLampiran);
            if (!($pathSPPK !== true and is_dir($pathSPPK))) {
                mkdir($folderLampiran, 0755, true);
            }
            $file->move($folderLampiran, $filenameLampiran);
            $batch = DB::table('batch_gaji_per_bulan')
                        ->where('id', $request->id)
                        ->first();
            $prev = null;
            if ($batch) {
                $prev = DB::table('batch_gaji_per_bulan')
                            ->where('tanggal_input', '<', $batch->tanggal_input)
                            ->where('kd_entitas', $batch->kd_entitas)
                            ->where('status', 'proses')
                            ->orderByDesc('tanggal_input')
                            ->first();

                if ($prev) {
                    DB::rollBack();
                    Alert::error('Gagal', 'Harap lakukan final proses pada penghasilan yang sebelumnya terlebih dahulu');
                    return redirect()->route('gaji_perbulan.index');
                }
                else {
                    DB::table('transaksi_tunjangan')
                        ->join('gaji_per_bulan', 'gaji_per_bulan.nip', 'transaksi_tunjangan.nip')
                        ->join('batch_gaji_per_bulan', 'gaji_per_bulan.batch_id', 'batch_gaji_per_bulan.id')
                        ->where('batch_gaji_per_bulan.id', $request->id)
                        ->update([
                            'transaksi_tunjangan.is_lock' => 1
                        ]);
                    DB::table('penghasilan_tidak_teratur')
                        ->join('gaji_per_bulan', 'gaji_per_bulan.nip', 'penghasilan_tidak_teratur.nip')
                        ->join('batch_gaji_per_bulan', 'gaji_per_bulan.batch_id', 'batch_gaji_per_bulan.id')
                        ->where('batch_gaji_per_bulan.id', $request->id)
                        ->update([
                            'penghasilan_tidak_teratur.is_lock' => 1
                        ]);

                    DB::table('batch_gaji_per_bulan')
                        ->where('id', $request->id)
                        ->update([
                            'file' => $filenameLampiran,
                            'tanggal_upload' => Carbon::now(),
                            'status' => 'final',
                            'tanggal_final' => date('Y-m-d'),
                            'updated_at' => now(),
                        ]);
                }
            }

            DB::commit();
            Alert::success('Sukses','Berhasil melakukan proses finalisasi');
            return redirect()->route('gaji_perbulan.index');
        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return redirect()->route('gaji_perbulan.index');
        } catch (QueryException $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return redirect()->route('gaji_perbulan.index');
        }
    }

    public function downloadRincianPayroll(Request $request){
        $is_cabang = auth()->user()->hasRole('cabang');
        $kantor = $is_cabang ? auth()->user()->kd_cabang : 'pusat';
        $batch_id = $request->batch_id;
        $batch = DB::table('batch_gaji_per_bulan AS batch')
                    ->select('batch.*', 'c.nama_cabang')
                    ->join('mst_cabang AS c', 'c.kd_cabang', 'batch.kd_entitas')
                    ->where('id', $batch_id)
                    ->first();
        $nama_kantor = 'undifined';
        if ($batch) {
            $nama_kantor = $batch->nama_cabang;
        }
        $cetak = $request->cetak ?? null;
        $data_batch = GajiPerBulanModel::where('batch_id', $batch_id)->select('bulan', 'tahun')->first();
        $bulan = $data_batch->bulan;
        $tahun = $data_batch->tahun;
        $tipe = $request->tipe;
        $bulanShow = array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );

        $payrollRepo = new PayrollRepository;
        $data = $payrollRepo->getJson($kantor, $bulan, $tahun, $cetak, $batch_id);
        $returnType = null;
        if($tipe == 'payroll'){
            $returnType = new ProsesPayroll($data);
        } else {
            $returnType = new ProsesRincianPayroll($data);
        }
// return $data;
        $filename = ucwords($tipe) . ' Kantor ' . $nama_kantor . ' Bulan ' . $bulanShow[$bulan] . ' Tahun ' . $tahun . '.xlsx';
        return Excel::download($returnType , $filename);
    }

    public function delete($id, Request $request)
    {
        // return $request;
        try {
            $id_batch = Request()->id;
            DB::table('batch_gaji_per_bulan')->where('id',$id_batch)->update([
                'deleted_at' => now()
            ]);

            Alert::success('Data berhasil dihapus.');
            return redirect()->route('gaji_perbulan.index');
        } catch (\Exception $e) {
            //  dd($e->getMessage());
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            //  dd($e->getMessage());
            Alert::error('Error', $e->getMessage());
            return back();
        }
    }

    public function restore(Request $request)
    {
        try {
            $id_batch = Request()->id;
            DB::table('batch_gaji_per_bulan')->where('id', $id_batch)->update([
                'deleted_at' => null
            ]);

            Alert::success('Data berhasil dikembalikan.');
            return redirect()->route('gaji_perbulan.index');
        } catch (\Exception $e) {
            //  dd($e->getMessage());
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            //  dd($e->getMessage());
            Alert::error('Error', $e->getMessage());
            return back();
        }
    }
}
