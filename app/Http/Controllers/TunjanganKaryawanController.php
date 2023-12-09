<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TunjanganKaryawanController extends Controller
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
        // Need permission
        $data = DB::table('tunjangan_karyawan')
            ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
            ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
            ->select(
                'tunjangan_karyawan.id',
                'mst_karyawan.nip',
                'mst_karyawan.nama_karyawan',
                'mst_tunjangan.nama_tunjangan',
                'tunjangan_karyawan.nominal',
            )
            ->get();

        return view('tunjangan_karyawan.index', ['data' => $data]);
    }

    public function getdatatunjangan(Request $request)
    {
        $data = DB::table('mst_karyawan')
            ->where('nip', $request->nip)
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->first();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
        $data = DB::table('mst_tunjangan')
            ->get();

        return view('tunjangan_karyawan.add', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Need permission
        try{
            DB::table('tunjangan_karyawan')
                ->insert([
                    'nip' => $request->get('nip'),
                    'id_tunjangan' => $request->get('tunjangan'),
                    'nominal' => $request->get('nominal')
                ]);

                Alert::success('Berhasil', 'Berhasil menambahkan tunjangan karyawan.');
                return redirect()->route('tunjangan_karyawan.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('tunjangan_karyawan.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('tunjangan_karyawan.index');
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
        $data = DB::table('tunjangan_karyawan')
            ->where('id', $id)
            ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
            ->first();
        $data_tunjangan = DB::table('mst_tunjangan')
            ->get();

        return view('tunjangan_karyawan.edit', ['data' => $data, 'data_tunjangan' => $data_tunjangan]);
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
        try{
            DB::table('tunjangan_karyawan')
                ->where('id', $id)
                ->update([
                    'nip' => $request->get('nip'),
                    'id_tunjangan' => $request->get('tunjangan'),
                    'nominal' => $request->get('nominal'),
                    'updated_at' => now()
                ]);

                Alert::success('Berhasil', 'Berhasil mengupdate tunjangan karyawan.');
                return redirect()->route('tunjangan_karyawan.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('tunjangan_karyawan.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('tunjangan_karyawan.index');
        }
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
