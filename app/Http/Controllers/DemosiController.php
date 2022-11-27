<?php

namespace App\Http\Controllers;

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
            ->where('keterangan', 'Demosi Pangkat')
            ->select('golongan_lama', 'golongan_baru', 'demosi_promosi_pangkat.id', 'mst_karyawan.nip', 'tanggal_pengesahan', 'bukti_sk', 'mst_karyawan.nama_karyawan', 'pg_lama.pangkat as pangkat_lama', 'pg_lama.golongan as golongan_lama', 'pg_baru.pangkat as pangkat_baru', 'pg_baru.golongan as golongan_baru')
            ->join('mst_pangkat_golongan as pg_lama', 'pg_lama.golongan', '=', 'demosi_promosi_pangkat.golongan_lama')
            ->join('mst_pangkat_golongan as pg_baru', 'pg_baru.golongan', '=', 'demosi_promosi_pangkat.golongan_baru')
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
        $data_panggol = DB::table('mst_pangkat_golongan')
            ->get();

        return view('demosi.add', ['data' => $data, 'data_panggol' => $data_panggol]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            DB::table('demosi_promosi_pangkat')
                ->insert([
                    'golongan_lama' => $request->get('golongan_lama'),
                    'golongan_baru' => $request->get('golongan_baru'),
                    'nip' => $request->get('nip'),
                    'tanggal_pengesahan' => $request->get('tanggal_pengesahan'),
                    'bukti_sk' => $request->get('bukti_sk'),
                    'keterangan' => 'Demosi Pangkat',
                ]);

            DB::table('mst_karyawan')
                ->where('nip', $request->nip)
                ->update([
                    'kd_panggol' => $request->get('golongan_baru'),
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil melakukan demosi pangkat.');
            return redirect()->route('demosi.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('demosi.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('demosi.index');
        }
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
