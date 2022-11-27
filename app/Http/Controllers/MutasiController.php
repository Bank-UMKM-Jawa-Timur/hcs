<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MutasiController extends Controller
{
    public function getDataKaryawan(Request $request)
    {
        $data = DB::table('mst_karyawan')
            ->where('nip', $request->nip)
            ->first();
        if($data->id_cabang != null){
            $data_kantor = DB::table('mst_karyawan')
                ->where('nip', $request->nip)
                ->join('mst_cabang', 'mst_cabang.id', '=', 'mst_karyawan.id_cabang')
                ->join('mst_jabatan', 'mst_jabatan.id', '=', 'mst_karyawan.id_jabatan')
                ->first();
        } else if($data->id_subdivisi != null){
            $data_kantor = DB::table('mst_karyawan')
                ->where('nip', $request->nip)
                ->join('mst_sub_divisi', 'mst_sub_divisi.id', '=', 'mst_karyawan.id_subdivisi')
                ->join('mst_jabatan', 'mst_jabatan.id', '=', 'mst_karyawan.id_jabatan')
                ->first();
        }

        return response()->json($data_kantor);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('mutasi')
            ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'mutasi.nip')
            ->join('mst_cabang', 'mst_cabang.id', '=', 'mutasi.id_cabang_baru')
            ->get();

            // dd($data)

        return view('mutasi.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = DB::table('mst_karyawan')
            ->select('nip', 'nama_karyawan', 'id_jabatan')
            ->get();
        $data_jabatan = DB::table('mst_jabatan')
            ->get();

        return view('mutasi.add', ['data' => $data, 'data_jabatan' => $data_jabatan]);
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
            if($request->get('id_jabatan_baru' == null)){
                DB::table('mutasi')
                ->insert([
                    'nip' => $request->get('nip'),
                    'id_subdiv_lama' => $request->get('id_subdiv_lama'),
                    'id_subdiv_baru' => $request->get('id_subdiv_baru'),
                    'id_cabang_lama' => $request->get('id_cabang_lama'),
                    'id_cabang_baru' => $request->get('id_cabang_baru'),
                    'tanggal_pengesahan' => $request->get('tanggal_pengesahan'),
                    'bukti_sk' => $request->get('bukti_sk'),
                    'keterangan' => $request->get('keterangan'),
                    'created_at' => now()
                ]);

                DB::table('mst_karyawan')
                    ->where('nip', $request->get('nip'))
                    ->update([
                        'id_jabatan' => $request->get('id_jabatan_baru'),
                        'id_cabang' => $request->get('id_cabang_baru'),
                        'id_subdivisi' => $request->get('id_subdiv_baru'),
                        'updated_at' => now()
                    ]);
            } else if($request->get('id_subdiv_baru') == null){
                DB::table('mutasi')
                ->insert([
                    'nip' => $request->get('nip'),
                    'id_jabatan_lama' => $request->get('id_jabatan_lama'),
                    'id_jabatan_baru' => $request->get('id_jabatan_baru'),
                    'id_cabang_lama' => $request->get('id_cabang_lama'),
                    'id_cabang_baru' => $request->get('id_cabang_baru'),
                    'tanggal_pengesahan' => $request->get('tanggal_pengesahan'),
                    'bukti_sk' => $request->get('bukti_sk'),
                    'keterangan' => $request->get('keterangan'),
                    'created_at' => now()
                ]);

                DB::table('mst_karyawan')
                    ->where('nip', $request->get('nip'))
                    ->update([
                        'id_jabatan' => $request->get('id_jabatan_baru'),
                        'id_cabang' => $request->get('id_cabang_baru'),
                        'id_subdivisi' => $request->get('id_subdiv_baru'),
                        'updated_at' => now()
                    ]);
            } else if($request->get('id_cabang_baru') == null){
                DB::table('mutasi')
                ->insert([
                    'nip' => $request->get('nip'),
                    'id_cabang_lama' => $request->get('id_cabang_lama'),
                    'id_cabang_baru' => $request->get('id_cabang_baru'),
                    'tanggal_pengesahan' => $request->get('tanggal_pengesahan'),
                    'bukti_sk' => $request->get('bukti_sk'),
                    'keterangan' => $request->get('keterangan'),
                    'created_at' => now()
                ]);

                DB::table('mst_karyawan')
                    ->where('nip', $request->get('nip'))
                    ->update([
                        'id_jabatan' => $request->get('id_jabatan_baru'),
                        'id_cabang' => $request->get('id_cabang_baru'),
                        'id_subdivisi' => $request->get('id_subdiv_baru'),
                        'updated_at' => now()
                    ]);
            } else{
                DB::table('mutasi')
                    ->insert([
                        'nip' => $request->get('nip'),
                        'id_jabatan_lama' => $request->get('id_jabatan_lama'),
                        'id_jabatan_baru' => $request->get('id_jabatan_baru'),
                        'id_subdiv_lama' => $request->get('id_subdiv_lama'),
                        'id_subdiv_baru' => $request->get('id_subdiv_baru'),
                        'id_cabang_lama' => $request->get('id_cabang_lama'),
                        'id_cabang_baru' => $request->get('id_cabang_baru'),
                        'tanggal_pengesahan' => $request->get('tanggal_pengesahan'),
                        'bukti_sk' => $request->get('bukti_sk'),
                        'keterangan' => $request->get('keterangan'),
                        'created_at' => now()
                    ]);

                DB::table('mst_karyawan')
                    ->where('nip', $request->get('nip'))
                    ->update([
                        'id_jabatan' => $request->get('id_jabatan_baru'),
                        'id_cabang' => $request->get('id_cabang_baru'),
                        'id_subdivisi' => $request->get('id_subdiv_baru'),
                        'updated_at' => now()
                    ]);
            }

            Alert::success('Berhasil', 'Berhasil melakukan mutasi karyawan.');
            return redirect()->route('mutasi.index');
        }catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('mutasi.index');
        }catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('mutasi.index');
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
