<?php

namespace App\Http\Controllers;

use App\Models\JabatanModel;
use App\Models\KaryawanModel;
use App\Models\PanggolModel;
use App\Models\TunjanganModel;
use App\Models\UmurModel;
use App\Service\ClassificationService;
use App\Service\EntityService;
use Doctrine\DBAL\Query;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KlasifikasiController extends Controller
{
    private String $orderRaw;

    public function __construct()
    {
        $this->middleware('auth');
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manajemen karyawan - data karyawan - export karyawan')) {
            return view('roles.forbidden');
        }
        $jabatan = JabatanModel::all();
        $panggol = PanggolModel::all();

        return view('karyawan.klasifikasi', [
            'karyawan' => null,
            'status' => null,
            'jabatan' => $jabatan,
            'panggol' => $panggol,
        ]);
    }

    public function klasifikasi_data(Request $request)
    {
        $kantor = $request->kantor;
        $karyawan = collect();
        $status = 0;

        $jabatan = JabatanModel::all();
        $panggol = PanggolModel::all();
        $umur = UmurModel::all();
        $pendidikan = array('SD', 'SMP', 'SLTP', 'SLTA', 'SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3');

        if ($request->kategori == 1) {
            $karyawan = KaryawanModel::query();

            $status = 1;
        }

        if ($request->kategori == 2) {
            $subDivs = DB::table('mst_sub_divisi')->where('kd_divisi', $request->divisi)
                ->pluck('kd_subdiv');

            $bagians = DB::table('mst_bagian')->whereIn('kd_entitas', $subDivs)
                ->orWhere('kd_entitas', $request->divisi)
                ->pluck('kd_bagian');

            $karyawan = KaryawanModel::where('kd_entitas', $request->divisi)
                ->orWhereIn('kd_entitas', $subDivs)
                ->orWhereIn('kd_bagian', $bagians);

            $status = 2;
        }

        if ($request->kategori == 3) {
            $entitas = $request->subDivisi ?? $request->divisi;

            $bagian = DB::table('mst_bagian')->where('kd_entitas', $entitas)
                ->pluck('kd_bagian');

            $karyawan = KaryawanModel::where('kd_entitas', $entitas)
                ->orWhereIn('kd_bagian', $bagian);

            $status = 3;
        }

        if ($request->kategori == 4) {
            $karyawan = KaryawanModel::where('kd_bagian', $request->bagian)->whereNotNull('kd_bagian');
            $status = 4;
        }

        if ($request->kategori == 5) {
            if ($kantor == 'Cabang') $karyawan = KaryawanModel::where('kd_entitas', $request->cabang);

            if ($kantor == 'Pusat') {
                $cbgs = DB::table('mst_cabang')->pluck('kd_cabang');
                $karyawan = KaryawanModel::whereNotIn('kd_entitas', $cbgs)
                    ->orWhere('kd_entitas', null);
            }

            $status = 5;
        }

        if ($request->kategori == 6) {
            $karyawan = KaryawanModel::with('tunjangan', 'bagian');

            if ($kantor == 'Cabang') {
                $karyawan = KaryawanModel::with('tunjangan', 'bagian')
                    ->where('kd_entitas', $request->cabang);
            }

            if ($kantor == 'Pusat') {
                $cbgs = DB::table('mst_cabang')->pluck('kd_cabang');
                $karyawan = KaryawanModel::with('tunjangan', 'bagian')
                    ->whereNotIn('kd_entitas', $cbgs)
                    ->orWhere('kd_entitas', null);
            }

            $status = 6;
        }

        if ($request->kategori == 7) {
            $karyawan = KaryawanModel::where('mst_karyawan.pendidikan', $request->pendidikan);

            $status = 7;
        }

        if ($request->kategori == 8) {
            $umur->map(function ($usia) use (&$karyawan) {
                $karyawan->push(
                    KaryawanModel::selectRaw("mst_karyawan.*, IF(tgl_lahir IS NULL, 0, DATE_FORMAT(FROM_DAYS(DATEDIFF(now(), tgl_lahir)), '%Y')) AS umurSkrg")
                        ->havingBetween('umurSkrg', [$usia->u_awal, $usia->u_akhir])
                        ->whereNull('tgl_lahir')
                        ->orWhereNotNull('tgl_lahir')
                        ->get()
                );
            });

            $status = 8;
        }

        if ($request->kategori == 9) {
            $karyawan = KaryawanModel::where('mst_karyawan.kd_jabatan', $request->jabatan);

            $status = 9;
        }


        if ($request->kategori == 10) {
            $karyawan = KaryawanModel::where('mst_karyawan.kd_panggol', $request->panggol);

            $status = 10;
        }

        if ($request->kategori == 11) {
            $karyawan = KaryawanModel::where('mst_karyawan.status_karyawan', $request->status);

            $status = 11;
        }

        if ($request->kategori == 12){
            foreach($pendidikan as $pend){
                $karyawan->push(
                    KaryawanModel::where('pendidikan', $pend)
                        ->whereNull('tanggal_penonaktifan')
                        ->get()
                );
            }
            $status = 12;
        }

        if ($karyawan instanceof Builder) {
            $karyawan->with('keluarga');
            $karyawan->leftJoin('mst_jabatan', 'mst_jabatan.kd_jabatan', 'mst_karyawan.kd_jabatan');
            $karyawan->orderByRaw($this->orderRaw);
            $karyawan = $karyawan->get();
        }

        if ($request->kategori != 8 && $request->kategori != 12) {
            // not umur & jenjang pendidikan category
            foreach ($karyawan as $key => $value) {
                $arrIdTunjangan = [
                    1, //keluarga
                    4, //teller
                    2, //telepon
                    3, //jabatan
                    5, //perumahan
                    7, //pelaksana
                    6, //kemahalan
                    8, //kesejahteraan
                ];
                $arrTotalTunjangan = [];

                for ($i=0; $i < count($arrIdTunjangan); $i++) {
                    $getTunjangan = null;
                    if (isset($value->nip)) {
                        $getTunjangan = DB::table('tunjangan_karyawan AS tj')
                                            ->select(
                                                DB::raw('SUM(tj.nominal) AS total_tunjangan')
                                            )
                                            ->join('mst_tunjangan AS mtj', 'mtj.id', 'tj.id_tunjangan')
                                            ->where('tj.nip', $value->nip)
                                            ->where('mtj.id', $arrIdTunjangan[$i])
                                            ->groupBy('tj.nip')
                                            ->first();
                    }

                    array_push($arrTotalTunjangan, $getTunjangan ? (int)$getTunjangan->total_tunjangan : 0);
                }
                $value->all_tunjangan = $arrTotalTunjangan;

                $value->gaji_total = $value->gj_pokok + $value->gj_penyesuaian + array_sum($arrTotalTunjangan);
            }
        }

        return view('karyawan.klasifikasi', [
            'status' => $status,
            'karyawan' => $karyawan,
            'jabatan' => $jabatan,
            'panggol' => $panggol,
            'request' => $request,
            'umur' => $umur,
            'pendidikan' => $pendidikan
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
    public function show(Request $request)
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
