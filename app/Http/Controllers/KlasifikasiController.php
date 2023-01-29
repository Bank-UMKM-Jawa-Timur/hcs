<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Service\ClassificationService;
use App\Service\EntityService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KlasifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('karyawan.klasifikasi', [
            'karyawan' => null,
            'status' => null
        ]);
    }

    public function klasifikasi_data(Request $request)
    {
        $kantor = $request->kantor;
        $karyawan = collect();
        $status = 0;

        if ($request->kategori == 1) {
            $subDivs = DB::table('mst_sub_divisi')->where('kd_divisi', $request->divisi)
                ->pluck('kd_subdiv');

            $bagians = DB::table('mst_bagian')->whereIn('kd_entitas', $subDivs)
                ->orWhere('kd_entitas', $request->divisi)
                ->pluck('kd_bagian');

            $karyawan = KaryawanModel::where('kd_entitas', $request->divisi)
                ->orWhereIn('kd_entitas', $subDivs)
                ->orWhereIn('kd_bagian', $bagians);

            $status = 1;
        }

        if ($request->kategori == 2) {
            $entitas = $request->subDivisi ?? $request->divisi;

            $bagian = DB::table('mst_bagian')->where('kd_entitas', $entitas)
                ->pluck('kd_bagian');

            $karyawan = KaryawanModel::where('kd_entitas', $entitas)
                ->orWhereIn('kd_bagian', $bagian);

            $status = 2;
        }

        if ($request->kategori == 3) {
            $karyawan = KaryawanModel::where('kd_bagian', $request->bagian)->whereNotNull('kd_bagian');
            $status = 3;
        }

        if ($request->kategori == 4) {
            if ($kantor == 'Cabang') $karyawan = KaryawanModel::where('kd_entitas', $request->cabang);

            if ($kantor == 'Pusat') {
                $cbgs = DB::table('mst_cabang')->pluck('kd_cabang');
                $karyawan = KaryawanModel::whereNotIn('kd_entitas', $cbgs)
                    ->orWhere('kd_entitas', null);
            }

            $status = 4;
        }

        if ($karyawan instanceof Builder) {
            $karyawan->leftJoin('is', 'is.id', 'mst_karyawan.id_is');
            $karyawan->leftJoin('mst_jabatan', 'mst_jabatan.kd_jabatan', 'mst_karyawan.kd_jabatan');
            $karyawan = $karyawan->get();
        }

        return view('karyawan.klasifikasi', [
            'status' => $status,
            'karyawan' => $karyawan,
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
    public function show(Request $request)
    { {
            $kantor = $request->kantor;
            $divisi = $request->get('divisi');
            $subDivisi = $request->get('subDivisi');
            $bagian = $request->get('bagian');

            if ($request->kategori == 1) {
                $dataDivisi = DB::table('mst_divisi')
                    ->where('kd_divisi', $divisi)
                    ->select('kd_divisi')
                    ->get();
                $div = array();
                foreach ($dataDivisi  as $item) {
                    array_push($div, $item->kd_divisi);
                }
                $karyawan = DB::table('mst_karyawan')
                    ->where('kd_entitas', $div)
                    ->get();
                return view('karyawan.klasifikasi', [
                    'status' => 1,
                    'karyawan' => $karyawan,
                    'request' => $request,
                ]);
            } else if ($request->kategori == 2) {
                $dataSubDivisi = DB::table('mst_sub_divisi')
                    ->where('kd_subdiv', $subDivisi)
                    ->select('kd_subdiv')
                    ->get();
                $subdiv = array();
                foreach ($dataSubDivisi  as $item) {
                    array_push($subdiv, $item->kd_subdiv);
                }
                $karyawan = DB::table('mst_karyawan')
                    ->where('kd_entitas', $subdiv)
                    ->get();
                return view('karyawan.klasifikasi', [
                    'status' => 2,
                    'karyawan' => $karyawan,
                    'request' => $request,
                ]);
            } else if ($request->kategori == 3) {
                $databagian = DB::table('mst_jabatan')
                    ->where('kd_jabatan', $bagian)
                    ->select('kd_jabatan')
                    ->get();
                $bag = array();
                foreach ($databagian  as $item) {
                    array_push($bag, $item->kd_jabatan);
                }
                $karyawan = DB::table('mst_karyawan')
                    ->where('kd_jabatan', $bag)
                    ->get();
                return view('karyawan.klasifikasi', [
                    'status' => 3,
                    'karyawan' => $karyawan,
                    'request' => $request,
                ]);
            } else if ($request->kategori == 4) {
                if ($kantor == 'Pusat') {
                    $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
                    $cbg = array();
                    foreach ($cabang as $item) {
                        array_push($cbg, $item->kd_cabang);
                    }
                    $karyawan = DB::table('mst_karyawan')
                        ->whereNotIn('kd_entitas', $cbg)
                        ->orWhere('kd_entitas', null)
                        ->get();
                } elseif ($kantor == 'Cabang') {
                    $cabang = $request->get('cabang');
                    $karyawan = DB::table('mst_karyawan')
                        ->where('kd_entitas', $cabang)
                        ->get();
                }

                return view('karyawan.klasifikasi', [
                    'status' => 4,
                    'karyawan' => $karyawan,
                    'request' => $request,
                ]);
            }
        }
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
