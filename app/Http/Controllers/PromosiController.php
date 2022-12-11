<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PromosiController extends Controller
{
    public function getgolongan(Request $request)
    {
        $data = DB::table('mst_karyawan')
            ->where('nip', $request->get('nip'))
            ->join('mst_pangkat_golongan', 'mst_pangkat_golongan.golongan', '=', 'mst_karyawan.kd_panggol')
            ->first();

        return response()->json($data);
    }

    public function getdatapromosi(Request $request)
    {
        $data = DB::table('mst_karyawan')
            ->where('nip', $request->nip)
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
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
            ->where('keterangan', 'Promosi Jabatan')
            ->select('kd_jabatan_lama', 'kd_jabatan_baru', 'demosi_promosi_pangkat.id', 'mst_karyawan.nip', 'tanggal_pengesahan', 'bukti_sk', 'mst_karyawan.nama_karyawan', 'pg_lama.nama_jabatan as jabatan_lama', 'pg_baru.nama_jabatan as jabatan_baru')
            ->join('mst_jabatan as pg_lama', 'pg_lama.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_lama')
            ->join('mst_jabatan as pg_baru', 'pg_baru.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_baru')
            ->join('mst_karyawan', 'demosi_promosi_pangkat.nip', '=', 'mst_karyawan.nip')
            ->get();

            // dd($data);

        return view('promosi.index', ['data' => $data]);
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
        $data_panggol = DB::table('mst_jabatan')
            ->get();

        return view('promosi.add', ['data' => $data, 'jabatan' => $data_panggol]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|alpha_num',
            'jabatan_baru' => 'required|not_in:-',
            'kantor' => 'required|not_in:-',
            'tanggal_pengesahan' => 'required',
            'bukti_sk' => 'required',
        ], [
            'nip.required' => 'Data harus diisi.',
            'nip.alpha_num' => 'NIP berupa alfa numerik.',
            'jabatan_baru.required' => 'Data harus diisi.',
            'jabatan_baru.not_in' => 'Data harus diisi',
            'tanggal_pengesahan.required' => 'Data harus diisi.',
            'bukti_sk.required' => 'Data harus diisi.',
        ]);

        try{
            $entitas = null;
            if($request->get('subdiv') != null){
                $entitas = $request->get('subdiv');
            } else if($request->get('cabang') != null){
                $entitas = $request->get('cabang');
            } else{
                $entitas = $request->get('divisi');
            }

            DB::table('demosi_promosi_pangkat')
                ->insert([
                    'nip' => $request->get('nip'),
                    'tanggal_pengesahan' => $request->get('tanggal_pengesahan'),
                    'bukti_sk' => $request->get('bukti_sk'),
                    'keterangan' => 'Promosi Jabatan',
                    'kd_jabatan_lama' => $request->get('jabatan_lama'),
                    'kd_jabatan_baru' => $request->get('jabatan_baru'),
                    'kd_entitas_lama' => $request->get('kantor_lama'),
                    'kd_entitas_baru' => $entitas,
                    'created_at' => now()
                ]);

            DB::table('mst_karyawan')
                ->where('nip', $request->nip)
                ->update([
                    'kd_jabatan' => $request->get('jabatan_baru'),
                    'ket_jabatan' => $request->get('ket_jabatan'),
                    'kd_entitas' => $entitas,
                    'kd_bagian' => $request->get('bagian'),
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil melakukan promosi pangkat.');
            return redirect()->route('promosi.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('promosi.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', 'Gagal melakukan promosi pangkat.'.$e->getMessage());
            return redirect()->route('promosi.index');
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
