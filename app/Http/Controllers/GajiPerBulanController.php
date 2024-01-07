<?php

namespace App\Http\Controllers;

use App\Imports\ImportPPH21;
use App\Models\GajiPerBulanModel;
use App\Models\PPHModel;
use App\Models\TunjanganModel;
use App\Repository\CetakGajiRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class GajiPerBulanController extends Controller
{
    private $param;

    public function __construct()
    {
        $this->param['namaTunjangan'] = [
            'tj_keluarga',
            'tj_telepon',
            'tj_jabatan',
            'tj_teller',
            'tj_perumahan',
            'tj_kemahalan',
            'tj_pelaksana',
            'tj_kesejahteraan',
            'tj_khusus',
            'tj_multilevel',
            'tj_ti',
            'tj_transport',
            'tj_pulsa',
            'tj_vitamin',
            'uang_makan',
        ];
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

    public function index()
    {
        if (!auth()->user()->can('penghasilan')) {
            return view('roles.forbidden');
        }
        $is_cabang = auth()->user()->hasRole('cabang');
        $is_pusat = auth()->user()->hasRole('kepegawaian');
        $kd_cabang = DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();
        // Need permission
        $data = DB::table('batch_gaji_per_bulan AS batch')
            ->join('gaji_per_bulan AS gaji', 'gaji.batch_id', 'batch.id')
            ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
            ->select(
                'batch.id',
                'batch.file',
                'batch.tanggal_cetak',
                'batch.tanggal_input',
                'batch.tanggal_final',
                'batch.status',
                'gaji.bulan',
                'gaji.tahun',
                DB::raw('CAST(SUM(gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_khusus + gaji.tj_multilevel + gaji.tj_ti) AS UNSIGNED) AS total')
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
            ->orderBy('gaji.created_at', 'desc')
            ->groupBy('gaji.tahun')
            ->groupBy('gaji.bulan')
            ->get();

        if ($is_cabang) {
            $kd_cabang = auth()->user()->kd_cabang;
            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                                    ->where('mst_profil_kantor.kd_cabang', $kd_cabang)
                                    ->where('active', 1)
                                    ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                                    ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                                    ->first();
            $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                                    ->where('kd_cabang', $kd_cabang)
                                    ->where('active', 1)
                                    ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                                    ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                                    ->first();
        }
        else {
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
        foreach ($data as $key => $item) {
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
            if ($hitungan_penambah && $hitungan_pengurang) {
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
            if ($is_cabang) {
                $karyawan = DB::table('gaji_per_bulan AS gaji')
                                ->join('mst_karyawan', 'mst_karyawan.nip', 'gaji.nip')
                                ->leftJoin('potongan_gaji AS potongan', 'potongan.nip', 'mst_karyawan.nip')
                                ->where('mst_karyawan.kd_entitas', $kd_cabang)
                                ->where('gaji.bulan', $item->bulan)
                                ->where('gaji.tahun', $item->tahun)
                                ->select(
                                    'mst_karyawan.*',
                                    'gaji.*',
                                    DB::raw('CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_khusus + gaji.tj_multilevel + gaji.tj_ti) AS UNSIGNED) AS total'),
                                    'potongan.kredit_koperasi',
                                    'potongan.iuran_koperasi',
                                    'potongan.kredit_pegawai',
                                    'potongan.iuran_ik',
                                    DB::raw('CAST((potongan.kredit_koperasi + potongan.iuran_koperasi + potongan.kredit_pegawai + potongan.iuran_ik) AS UNSIGNED) AS total_potongan'),
                                )
                                ->get();
            }
            else {
                $karyawan = DB::table('gaji_per_bulan AS gaji')
                            ->join('mst_karyawan', 'mst_karyawan.nip', 'gaji.nip')
                            ->leftJoin('potongan_gaji AS potongan', 'potongan.nip', 'mst_karyawan.nip')
                            ->where(function($q2) use ($kd_cabang) {
                                $q2->whereNotIn('mst_karyawan.kd_entitas', $kd_cabang)
                                    ->orWhere('mst_karyawan.kd_entitas', 0)
                                    ->orWhereNull('mst_karyawan.kd_entitas');
                            })
                            ->where('gaji.bulan', $item->bulan)
                            ->where('gaji.tahun', $item->tahun)
                            ->select(
                                'mst_karyawan.*',
                                'gaji.*',
                                DB::raw('CAST((gaji.gj_pokok + gaji.gj_penyesuaian + gaji.tj_keluarga + gaji.tj_telepon + gaji.tj_jabatan + gaji.tj_teller + gaji.tj_perumahan + gaji.tj_kemahalan + gaji.tj_pelaksana + gaji.tj_kesejahteraan + gaji.tj_khusus + gaji.tj_multilevel + gaji.tj_ti) AS UNSIGNED) AS total'),
                                'potongan.kredit_koperasi',
                                'potongan.iuran_koperasi',
                                'potongan.kredit_pegawai',
                                'potongan.iuran_ik',
                                DB::raw('CAST((potongan.kredit_koperasi + potongan.iuran_koperasi + potongan.kredit_pegawai + potongan.iuran_ik) AS UNSIGNED) AS total_potongan'),
                            )
                            ->get();
            }
            // Get Bruto & Neto
            $bruto = 0;
            $neto = 0;
            $total_potongan = 0;
            foreach ($karyawan as $value) {
                $total_gaji = $value->total;
                if($total_gaji > 0){
                    // Get Bruto
                    $jkk = 0;
                    $jht = 0;
                    $jkm = 0;
                    $kesehatan = 0;
                    $jp_penambah = 0;
                    if($value->tanggal_penonaktifan == null){
                        $jkk = ($persen_jkk / 100) * $total_gaji;
                        $jht = ($persen_jht / 100) * $total_gaji;
                        $jkm = ($persen_jkm / 100) * $total_gaji;
                        $jp_penambah = ($persen_jp_penambah / 100) * $total_gaji;
                    }

                    if($value->jkn != null){
                        if($total_gaji > $batas_atas){
                            $kesehatan = ($batas_atas * ($persen_kesehatan / 100));
                        } else if($total_gaji < $batas_bawah){
                            $kesehatan = ($batas_bawah * ($persen_kesehatan / 100));
                        } else{
                            $kesehatan = ($total_gaji * ($persen_kesehatan / 100));
                        }
                    }
                    $bruto += round($jkk + $jht + $jkm + $kesehatan + $jp_penambah);

                    // Get Neto
                    $total_potongan += $value->total_potongan;
                }
            }
            $item->bruto = $bruto;
            $neto = $bruto - $total_potongan;
            $item->neto = $neto;
            $item->potongan = $total_potongan;
        }
        return view('gaji_perbulan.index', ['data_gaji' => $data]);
    }

    public function penyesuaianDataJson(Request $request) {
        try {
            $batch_id = $request->batch_id;
            $data_gaji = DB::table('gaji_per_bulan AS gaji')
                            ->select('gaji.*', 'm.nama_karyawan')
                            ->join('mst_karyawan AS m', 'm.nip', 'gaji.nip')
                            ->where('gaji.batch_id', $batch_id)
                            ->get();

            foreach ($data_gaji as $key => $gaji) {
                $new_data = [];
                $jumlah_penyesuaian = 0;
                $karyawan = DB::table('mst_karyawan')
                            ->where('nip', $gaji->nip)
                            ->first();
                if ($gaji->gj_pokok != $karyawan->gj_pokok) {
                    $item = [
                        'gj_pokok' => $gaji->gj_pokok,
                        'gj_pokok_baru' => $karyawan->gj_pokok,
                    ];
                    array_push($new_data, $item);
                }
                if ($gaji->gj_penyesuaian != $karyawan->gj_penyesuaian) {
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
                            $item = [
                                'tj_keluarga' => $gaji->tj_keluarga,
                                'tj_keluarga_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Telepon
                    if ($tunj->id_tunjangan == 2) {
                        if ($gaji->tj_telepon != $tunj->nominal) {
                            $item = [
                                'tj_telepon' => $gaji->tj_telepon,
                                'tj_telepon_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Jabatan
                    if ($tunj->id_tunjangan == 3) {
                        if ($gaji->tj_jabatan != $tunj->nominal) {
                            $item = [
                                'tj_jabatan' => $gaji->tj_jabatan,
                                'tj_jabatan_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Teller
                    if ($tunj->id_tunjangan == 4) {
                        if ($gaji->tj_teller != $tunj->nominal) {
                            $item = [
                                'tj_teller' => $gaji->tj_teller,
                                'tj_teller_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Perumahan
                    if ($tunj->id_tunjangan == 5) {
                        if ($gaji->tj_perumahan != $tunj->nominal) {
                            $item = [
                                'tj_perumahan' => $gaji->tj_perumahan,
                                'tj_perumahan_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Kemahalan
                    if ($tunj->id_tunjangan == 6) {
                        if ($gaji->tj_kemahalan != $tunj->nominal) {
                            $item = [
                                'tj_kemahalan' => $gaji->tj_kemahalan,
                                'tj_kemahalan_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Pelaksana
                    if ($tunj->id_tunjangan == 7) {
                        if ($gaji->tj_pelaksana != $tunj->nominal) {
                            $item = [
                                'tj_pelaksana' => $gaji->tj_pelaksana,
                                'tj_pelaksana_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Kesejahteraan
                    if ($tunj->id_tunjangan == 8) {
                        if ($gaji->tj_kesejahteraan != $tunj->nominal) {
                            $item = [
                                'tj_kesejahteraan' => $gaji->tj_kesejahteraan,
                                'tj_kesejahteraan_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Khusus
                    if ($tunj->id_tunjangan == 30) {
                        if ($gaji->tj_khusus != $tunj->nominal) {
                            $item = [
                                'tj_khusus' => $gaji->tj_khusus,
                                'tj_khusus_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Multilevel
                    if ($tunj->id_tunjangan == 9) {
                        if ($gaji->tj_multilevel != $tunj->nominal) {
                            $item = [
                                'tj_multilevel' => $gaji->tj_multilevel,
                                'tj_multilevel_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // TI
                    if ($tunj->id_tunjangan == 10) {
                        if ($gaji->tj_ti != $tunj->nominal) {
                            $item = [
                                'tj_ti' => $gaji->tj_ti,
                                'tj_ti_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
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
                            $item = [
                                'tj_transport' => $gaji->tj_transport,
                                'tj_transport_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Pulsa
                    if ($tunj->id_tunjangan == 12) {
                        if ($gaji->tj_pulsa != $tunj->nominal) {
                            $item = [
                                'tj_pulsa' => $gaji->tj_pulsa,
                                'tj_pulsa_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Vitamin
                    if ($tunj->id_tunjangan == 13) {
                        if ($gaji->tj_vitamin != $tunj->nominal) {
                            $item = [
                                'tj_vitamin' => $gaji->tj_vitamin,
                                'tj_vitamin_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                    // Uang Makan
                    if ($tunj->id_tunjangan == 14) {
                        if ($gaji->uang_makan != $tunj->nominal) {
                            $item = [
                                'uang_makan' => $gaji->uang_makan,
                                'uang_makan_baru' => $tunj->nominal,
                            ];
                            array_push($new_data, $item);
                        }
                    }
                }

                if (count($new_data) == 0) {
                    unset($data_gaji[$key]);
                } else {
                    $gaji->penyesuaian = $new_data;
                }
            }
            return DataTables::of($data_gaji)->make(true);
        }
        catch (Exception $e) {
            return $e->getMessage();
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

        $this->validate($request, [
            'tahun' => 'required',
            'bulan' => 'required',
            'tanggal' => 'required',
        ], [
            'not_in' => ':attribute harus dipilih.',
            'required' => ':attribute harus diisi.',
        ], [
            'tahun' => 'Tahun',
            'bulan' => 'Bulan',
            'tanggal' => 'Tanggal',
        ]);

        DB::beginTransaction();
        try {
            if ($request->has('batch_id')) {
                $batch = DB::table('batch_gaji_per_bulan')->find($request->batch_id);
                $bulan = (int) date('m', strtotime($batch->tanggal_input));
                $tahun = date('Y', strtotime($batch->tanggal_input));
                $tanggal = $batch->tanggal_input;
            }
            else {
                $bulan = $request->bulan;
                $tahun = $request->tahun;
                $tanggal = $request->tanggal;
            }
            $tunjangan = array();
            $tjJamsostek = array();
            $cabang = DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();

            $now = date('Y-m-d H:i:s');
            if ($request->has('batch_id')) {
                $batch = [
                    'updated_at' => $now,
                ];
                DB::table('batch_gaji_per_bulan')->update($batch);
            }
            else {
                $batch = [
                    'tanggal_input' => $tanggal,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $batch_id = DB::table('batch_gaji_per_bulan')->insertGetId($batch);
            }

            if (auth()->user()->hasRole('cabang')) {
                // Cabang
                $karyawan = DB::table('mst_karyawan')
                            ->whereNull('tanggal_penonaktifan')
                            ->where('kd_entitas', auth()->user()->kd_cabang)
                            ->get();
            }
            else {
                $is_pusat = auth()->user()->hasRole('kepegawaian');
                $kd_cabang = DB::table('mst_cabang')
                                ->select('kd_cabang')
                                ->pluck('kd_cabang')
                                ->toArray();
                $karyawan = DB::table('mst_karyawan')
                                ->whereNull('tanggal_penonaktifan')
                                ->when($is_pusat, function($query) use ($kd_cabang) {
                                    $query->where(function($q2) use ($kd_cabang) {
                                        $q2->whereNotIn('mst_karyawan.kd_entitas', $kd_cabang)
                                            ->orWhere('mst_karyawan.kd_entitas', 0)
                                            ->orWhereNull('mst_karyawan.kd_entitas');
                                    });
                                })
                                ->get();
            }

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

                // Get status pernikahan untuk kode ptkp
                if ($item->status == 'K' || $item->status == 'Kawin') {
                    $anak = DB::table('mst_karyawan')
                        ->where('keluarga.nip', $item->nip)
                        ->join('keluarga', 'keluarga.nip', 'mst_karyawan.nip')
                        ->orderByDesc('id')
                        ->first('jml_anak');
                    if ($anak != null && $anak->jml_anak > 3) {
                        $status = 'K/3';
                    } else if ($anak != null) {
                        $status = 'K/' . $anak->jml_anak;
                    } else {
                        $status = 'K/0';
                    }
                }
                else {
                    $status = 'TK';
                }

                // Get PTKP
                $ptkp = DB::table('set_ptkp')
                        ->where('kode', $status)
                        ->first();

                // Get penambah & pengurang bruto
                if (!$item->kd_entitas) {
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
                else if (in_array($item->kd_entitas, $cabang)) {
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
                        'dpp' => $tunjangan[14],
                        'tj_khusus' => $tunjangan[15],
                        'updated_at' => $now
                    ];
                    $gaji = GajiPerBulanModel::where('batch_id', $request->batch_id)
                                            ->where('nip', $item->nip)
                                            ->where('bulan', $bulan)
                                            ->where('tahun', $tahun)
                                            ->first();
                    GajiPerBulanModel::where('batch_id', $request->batch_id)
                                                ->where('nip', $item->nip)
                                                ->where('bulan', $bulan)
                                                ->where('tahun', $tahun)
                                                ->update($employee);

                    $pph = [
                        'gaji_per_bulan_id' => $gaji->id,
                        'nip' => $item->nip,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'total_pph' => $this->getPPHBulanIni($bulan, $tahun, $item, $ptkp),
                        'updated_at' => $now
                    ];
                    PPHModel::where('gaji_per_bulan_id', $gaji->id)
                            ->where('nip', $item->nip)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->update($pph);
                }
                else {
                    $gaji = DB::table('gaji_per_bulan')
                                ->where('nip', $item->nip)
                                ->where('bulan', $bulan)
                                ->where('tahun', $tahun)
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
                            'dpp' => $tunjangan[14],
                            'tj_khusus' => $tunjangan[15],
                            'created_at' => now()
                        ];
                        $gaji_id = GajiPerBulanModel::insertGetId($employee);
                        $pph_bulan_ini = DB::table('pph_yang_dilunasi')
                                            ->where('nip', $item->nip)
                                            ->where('bulan', $bulan)
                                            ->where('tahun', $tahun)
                                            ->first();
                        if (!$pph_bulan_ini) {
                            $pph = [
                                'gaji_per_bulan_id' => $gaji_id,
                                'nip' => $item->nip,
                                'bulan' => $bulan,
                                'tahun' => $tahun,
                                'total_pph' => $this->getPPHBulanIni($bulan, $tahun, $item, $ptkp),
                                'tanggal' => now(),
                                'created_at' => now()
                            ];
                            PPHModel::insert($pph);
                        }
                    }
                }
            }

            DB::commit();
            if ($request->has('batch_id')) {
                Alert::success('Berhasil', 'Berhasil memperbarui penghasilan karyawan.');
            }
            else {
                Alert::success('Berhasil', 'Berhasil melakukan pembayaran penghaslan karyawan.');
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
            DB::table('batch_gaji_per_bulan')
                ->where('id', $request->batch_id)
                ->update([
                    'status' => 'final',
                    'tanggal_final' => date('Y-m-d'),
                    'updated_at' => now(),
                ]);

            DB::commit();
            Alert::success('Berhasil', 'Berhasil memproses penghasilan.');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return back();
        }
    }

    function getPPHBulanIni($bulan, $tahun, $karyawan, $ptkp)
    {
        $tanggal = date('Y-m-d', strtotime(Carbon::createFromFormat('Y-m-d', date('Y') . '-' . $bulan . '-' . '26')));
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
                    if ($keyTunjangan < 10)
                        array_push($tunjanganJamsostek, $gaji->$item);
                }

                $totalGj = $gaji->gj_pokok + $gaji->gj_penyesuaian;
                $totalGjJamsotek = $totalGj + array_sum($tunjanganJamsostek);
                $totalGj += $penghasilanTidakTeratur + array_sum($tunjangan) + $this->getPenambah($totalGjJamsotek, $karyawan->jkn);
                array_push($pengurang, $this->getPengurang($karyawan->status_karyawan, $gaji->tj_keluarga, $gaji->tj_kesejahteraan, $totalGjJamsotek, $gaji->gj_pokok));
                array_push($totalGaji, $totalGj);
                array_push($totalGajiJamsostek, $totalGjJamsotek);
                array_push($penambah, $this->getPenambah($totalGjJamsotek, $karyawan->jkn));
            }
            $totalGajiBulanIni = $karyawan->gj_pokok + $karyawan->gj_penyesuaian;
            $totalGjJamsostekBulanIni = $totalGajiBulanIni + $tjJamsostekBulanIni;
            $totalGajiBulanIni += $penghasilanTidakTeraturBulanIni + $tunjanganBulanIni  + $this->getPenambah($totalGjJamsostekBulanIni, $karyawan->jkn);
            $bonus += $bonusBulanIni;

            array_push($pengurang, $this->getPengurang($karyawan->status_karyawan, $tKeluarga, $tKesejahteraan, $totalGjJamsostekBulanIni, $karyawan->gj_pokok));
            array_push($totalGaji, $totalGajiBulanIni);
            array_push($totalGajiJamsostek, $totalGjJamsostekBulanIni);
            array_push($penambah, $this->getPenambah($totalGjJamsostekBulanIni, $karyawan->jkn));
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
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
                ->sum('penghasilan_tidak_teratur.nominal');
            $bonus = DB::table('penghasilan_tidak_teratur')
                ->join('mst_tunjangan AS m', 'm.id', 'penghasilan_tidak_teratur.id_tunjangan')
                ->where('m.kategori', 'bonus')
                ->where('nip', $karyawan->nip)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->whereDate('penghasilan_tidak_teratur.created_at', '<', $tanggal)
                ->sum('penghasilan_tidak_teratur.nominal');
            $totalGajiBulanIni = $karyawan->gj_pokok + $karyawan->gj_penyesuaian;
            $totalGjJamsostekBulanIni = $totalGajiBulanIni + $tjJamsostekBulanIni;
            $totalGajiBulanIni += $penghasilanTidakTeraturBulanIni + $tunjanganBulanIni + $this->getPenambah($totalGjJamsostekBulanIni, $karyawan->jkn);

            array_push($pengurang, $this->getPengurang($karyawan->status_karyawan, $tKeluarga, $tKesejahteraan, $totalGjJamsostekBulanIni, $karyawan->gj_pokok));
            array_push($totalGaji, $totalGajiBulanIni);
            array_push($totalGajiJamsostek, $totalGjJamsostekBulanIni);
            array_push($penambah, $this->getPenambah($totalGjJamsostekBulanIni, $karyawan->jkn));
        }

        $lima_persen = ceil(0.05 * array_sum($totalGaji));
        $keterangan = 500000 * intval($bulan);
        $biaya_jabatan = 0;
        if ($lima_persen > $keterangan) {
            $biaya_jabatan = $keterangan;
        } else {
            $biaya_jabatan = $lima_persen;
        }
        $rumus_14 = 0;
        if (0.05 * (array_sum($totalGaji)) > $keterangan) {
            $rumus_14 = round($keterangan);
        } else {
            $rumus_14 = round(0.05 * (array_sum($totalGaji)));
        }
        $no_14 = round((array_sum($totalGaji) - $bonus - array_sum($pengurang) - $biaya_jabatan) / intval($bulan) * 12 + $bonus + ($biaya_jabatan - $rumus_14));

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
        if (intval($bulan) > 1) {
            $pphTerbayar = DB::table('pph_yang_dilunasi')
                ->where('nip', $karyawan->nip)
                ->where('tahun', $tahun)
                ->sum('total_pph');
            $pph -= $pphTerbayar;
        }
        return $pph;
    }

    function getPengurang($status, $tjKeluarga, $tjKesejahteraan, $totalGajiJamsostek, $gajiPokok)
    {
        $pengurang = 0;
        // Perhitungan pengurangan bruto
        if ($status == 'IKJP') {
            $pengurang = ($this->param['persenJpPengurang'] / 100) * $totalGajiJamsostek;
        } else {
            $dpp = ((($gajiPokok + $tjKeluarga) + ($tjKesejahteraan * 0.5)) * 0.05);
            if ($totalGajiJamsostek >= $this->param['nominalJp']) {
                $dppExtra = $this->param['nominalJp'] * ($this->param['persenJpPengurang'] / 100);
            } else {
                $dppExtra = $totalGajiJamsostek * ($this->param['persenJpPengurang'] / 100);
            }
            $pengurang = round($dpp + $dppExtra);
        }

        return $pengurang;
    }

    function getPenambah($totalGajiJamsostek, $jkn)
    {
        $penambah = 0;

        // Perhitungan penambah bruto
        $jkk = ($this->param['persenJkk'] / 100) * $totalGajiJamsostek;
        $jht = ($this->param['persenJht'] / 100) * $totalGajiJamsostek;
        $jkm = ($this->param['persenJkm'] / 100) * $totalGajiJamsostek;
        $jp = ($this->param['persenJpPenambah'] / 100) * $totalGajiJamsostek;
        if ($jkn != null) {
            if ($totalGajiJamsostek > $this->param['batasAtas']) {
                $kesehatan = $this->param['batasAtas'] * ($this->param['persenKesehatan'] / 100);
            } else if ($totalGajiJamsostek < $this->param['batasBawah']) {
                $kesehatan = $this->param['batasBawah'] * ($this->param['persenKesehatan'] / 100);
            } else {
                $kesehatan = $totalGajiJamsostek * ($this->param['persenKesehatan'] / 100);
            }
        } else {
            $kesehatan = 0;
        }

        $penambah = round($jkk + $jht + $jkm + $jp + $kesehatan);
        return $penambah;
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

    public function getPPHDesember($bulan, $tahun, $karyawan, $ptkp){
        // dd([$bulan, $tahun]);
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
                if ($keyTunjangan < 10)
                    array_push($tunjanganJamsostek, $gaji->$item);
            }

            $totalGj = $gaji->gj_pokok + $gaji->gj_penyesuaian;
            $totalGjJamsotek = $totalGj + array_sum($tunjanganJamsostek);
            $totalGj += $penghasilanTidakTeratur + array_sum($tunjangan) + $this->getPenambah($totalGjJamsotek, $karyawan->jkn);
            array_push($pengurang, $this->getPengurang($karyawan->status_karyawan, $gaji->tj_keluarga, $gaji->tj_kesejahteraan, $totalGjJamsotek, $gaji->gj_pokok));
            array_push($totalGaji, $totalGj);
            array_push($totalGajiJamsostek, $totalGjJamsotek);
            array_push($penambah, $this->getPenambah($totalGjJamsotek, $karyawan->jkn));
        }

        $lima_persen = ceil(0.05 * array_sum($totalGaji));
        $keterangan = 500000 * intval($bulan);
        $biaya_jabatan = 0;
        if ($lima_persen > $keterangan) {
            $biaya_jabatan = $keterangan;
        } else {
            $biaya_jabatan = $lima_persen;
        }
        $rumus_14 = 0;
        if (0.05 * (array_sum($totalGaji)) > $keterangan) {
            $rumus_14 = round($keterangan);
        } else {
            $rumus_14 = round(0.05 * (array_sum($totalGaji)));
        }
        $no_14 = round((array_sum($totalGaji) - $bonus - array_sum($pengurang) - $biaya_jabatan) / intval($bulan) * 12 + $bonus + ($biaya_jabatan - $rumus_14));

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

    public function storePPHDesember($nip, $tahun, $bulan){
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

            // Get status pernikahan untuk kode ptkp
            if ($item->status == 'K' || $item->status == 'Kawin') {
                $anak = DB::table('mst_karyawan')
                    ->where('keluarga.nip', $item->nip)
                    ->join('keluarga', 'keluarga.nip', 'mst_karyawan.nip')
                    ->orderByDesc('id')
                    ->first('jml_anak');
                if ($anak != null && $anak->jml_anak > 3) {
                    $status = 'K/3';
                } else if ($anak != null) {
                    $status = 'K/' . $anak->jml_anak;
                } else {
                    $status = 'K/0';
                }
            }
            else {
                $status = 'TK';
            }

            // Get PTKP
            $ptkp = DB::table('set_ptkp')
                    ->where('kode', $status)
                    ->first();

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

        return intval($this->getPPHDesember($bulan, $tahun, $item, $ptkp));
    }

    function cetak($id) {
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
        $is_pusat = auth()->user()->hasRole('kepegawaian');
        $is_cabang = auth()->user()->hasRole('cabang');
        if ($is_pusat) {
            $kantor = 'pusat';
        }
        if($is_cabang){
            $kantor = auth()->user()->kd_cabang;
        }
        $cetak = new CetakGajiRepository;
        $result = $cetak->cetak($kantor, $month, $year,$id);

        DB::table('batch_gaji_per_bulan')->where('id',$id)->update([
            'tanggal_cetak' => Carbon::now(),
            ]);
        $pdf = Pdf::loadView('gaji_perbulan.cetak-pdf', ['data' => $result,'month' => $month, 'year' => $year,'tanggal' => $data->tanggal_input]);
        return $pdf->download('LampiranGaji-' . $data->tanggal_input . '.pdf');
    }

    function upload(Request $request){
        $request->validate([
            'upload_file' => 'required'
        ]);
        try {
            $folderLampiran = public_path() . '/upload/' . $request->id . '/';
            $file = $request->upload_file;
            $filenameLampiran = $file->getClientOriginalName();
            $pathSPPK = realpath($folderLampiran);
            if (!($pathSPPK !== true and is_dir($pathSPPK))) {
                mkdir($folderLampiran, 0755, true);
            }
            $file->move($folderLampiran, $filenameLampiran);
            DB::table('batch_gaji_per_bulan')->where('id',$request->id)->update([
                'file' => $filenameLampiran,
            ]);
            Alert::success('Sukses','Lampiran Gaji Berhasil Diupload');
            return redirect()->route('gaji_perbulan.index');
        } catch (Exception $th) {
            return $th;
        } catch (QueryException $e) {
            return $e;
        }
    }
}
