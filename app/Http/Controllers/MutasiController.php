<?php

namespace App\Http\Controllers;

use App\Http\Requests\MutasiRequest;
use App\Service\EntityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MutasiController extends Controller
{
    public function getDataKaryawan(Request $request)
    {
        $officer = DB::table('mst_karyawan')->where('nip', $request->nip)->first();

        if(!$officer) return response()->json([
            'success' => false,
            'message' => 'Data karyawan tidak ditemukan',
        ]);

        $officer->entitas = EntityService::getEntity($officer->kd_entitas);
        $officer->jabatan = DB::table('mst_jabatan')->where('kd_jabatan', $officer->kd_jabatan)->first();

        return response()->json([
            'success' => true,
            'karyawan' => $officer,
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('mutasi')
            ->select(
                'mutasi.*',
                'karyawan.*',
                'newPos.nama_jabatan as jabatan_baru',
                'oldPos.nama_jabatan as jabatan_lama'
            )
            ->join('mst_karyawan as karyawan', 'karyawan.nip', '=', 'mutasi.nip')
            ->join('mst_jabatan as newPos', 'newPos.kd_jabatan', '=', 'mutasi.kd_jabatan_baru')
            ->join('mst_jabatan as oldPos', 'oldPos.kd_jabatan', '=', 'mutasi.kd_jabatan_lama')
            ->get();

        return view('mutasi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = DB::table('mst_karyawan')
            ->select('nip', 'nama_karyawan', 'kd_jabatan')
            ->get();
        $data_jabatan = DB::table('mst_jabatan')
            ->get();

        // dd($data);

        return view('mutasi.add', ['data' => $data, 'data_jabatan' => $data_jabatan]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MutasiRequest $request)
    {
        $entity = EntityService::getEntityFromRequest($request);
        $mutasi = DB::table('mutasi')
            ->insert([
                'nip' => $request->nip,
                'tanggal_pengesahan' => $request->tanggal_pengesahan,
                'bukti_sk' => $request->bukti_sk,
                'keterangan' => 'Mutasi Jabatan',
                'kd_entitas_lama' => $request->kd_entity,
                'kd_entitas_baru' => $entity,
                'kd_jabatan_lama' => $request->id_jabatan_lama,
                'kd_jabatan_baru' => $request->id_jabatan_baru,
                'created_at' => now(),
            ]);

        if(!$mutasi) {
            Alert::error('Error', 'Gagal menambahkan data mutasi');
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

        Alert::success('Berhasil', 'Berhasil menambahkan data mutasi');
        return redirect()->route('mutasi.index');
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
