<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemosiRequest;
use App\Service\EntityService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DemosiController extends Controller
{
    public function getgolongan(Request $request)
    {
        $data = DB::table('mst_karyawan')
            ->select('nip', 'nama_karyawan', 'mst_pangkat_golongan.golongan', 'mst_pangkat_golongan.pangkat')
            ->where('nip', $request->get('nip'))
            ->join('mst_pangkat_golongan', 'mst_pangkat_golongan.golongan', '=', 'mst_karyawan.kd_panggol')
            ->first();

        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('demosi_promosi_pangkat')
            ->where('keterangan', 'Demosi Jabatan')
            ->select('kd_jabatan_lama', 'kd_jabatan_baru', 'demosi_promosi_pangkat.id', 'mst_karyawan.nip', 'tanggal_pengesahan', 'bukti_sk', 'mst_karyawan.nama_karyawan', 'pg_lama.nama_jabatan as jabatan_lama', 'pg_baru.nama_jabatan as jabatan_baru')
            ->join('mst_jabatan as pg_lama', 'pg_lama.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_lama')
            ->join('mst_jabatan as pg_baru', 'pg_baru.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_baru')
            ->join('mst_karyawan', 'demosi_promosi_pangkat.nip', '=', 'mst_karyawan.nip')
            ->get();

            // dd($data);

        return view('demosi.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = DB::table('mst_karyawan')
            ->select('nip', 'nama_karyawan', 'kd_panggol')
            ->get();
        $data_jabatan = DB::table('mst_jabatan')
            ->get();

        return view('demosi.add', compact('data', 'data_jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DemosiRequest $request)
    {
        $entity = EntityService::getEntityFromRequest($request);
        $demosi = DB::table('demosi_promosi_pangkat')
            ->insert([
                'nip' => $request->nip,
                'tanggal_pengesahan' => $request->tanggal_pengesahan,
                'bukti_sk' => $request->bukti_sk,
                'keterangan' => 'Demosi Jabatan',
                'kd_entitas_lama' => $request->kd_entity,
                'kd_entitas_baru' => $entity,
                'kd_jabatan_lama' => $request->id_jabatan_lama,
                'kd_jabatan_baru' => $request->id_jabatan_baru,
                'created_at' => now(),
            ]);

        if(!$demosi) {
            Alert::error('Error', 'Gagal menambahkan data demosi');
            return back()->withInput();
        }

        $officer = DB::table('mst_karyawan')
            ->where('nip', $request->nip)
            ->update([
                'kd_jabatan' => $request->id_jabatan_baru,
                'ket_jabatan' => $request->keterangan,
                'kd_entitas' => $entity,
                'kd_bagian' => $request->kd_bagian,
                'updated_at' => now(),
            ]);

        if($officer < 1) {
            Alert::error('Error', 'Gagal mengupdate data karyawan');
            return back()->withInput();
        }

        Alert::success('Berhasil', 'Berhasil menambahkan data demosi');
        return redirect()->route('demosi.index');
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
