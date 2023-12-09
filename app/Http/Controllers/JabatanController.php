<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class JabatanController extends Controller
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
        $data = DB::table('mst_jabatan')
            ->get();
        return view('jabatan.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jabatan.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'kd_jabatan' => 'required',
            'nama_jabatan' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_jabatan')
                ->insert([
                    'kd_jabatan' => $request->get('kd_jabatan'),
                    'nama_jabatan' => $request->get('nama_jabatan'),
                    'created_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Menambah Jabatan.');
            return redirect()->route('jabatan.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', 'Kode Jabatan Telah Digunakan.');
            return redirect()->route('kantor.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Jabatan.');
            return redirect()->route('kantor.index')->withStatus($e->getMessage());
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
        // Need permission
        $data = DB::table('mst_jabatan')
            ->where('kd_jabatan', $id)
            ->first();

        return view('jabatan.edit', ['data' => $data]);
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
        $request->validate([
            'kd_jabatan' => 'required',
            'nama_jabatan' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_jabatan')
                ->where('kd_jabatan', $id)
                ->update([
                    'kd_jabatan' => $request->get('kd_jabatan'),
                    'nama_jabatan' => $request->get('nama_jabatan'),
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Mengupdate Jabatan.');
            return redirect()->route('jabatan.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', 'Kode Jabatan Telah Digunakan.');
            return redirect()->route('kantor.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Mengupdate Jabatan.');
            return redirect()->route('kantor.index')->withStatus($e->getMessage());
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
        // Need permission
        try{
            DB::table('mst_jabatan')
                ->where('id', $id)
                ->delete();

            Alert::success('Berhasil', 'Berhasil Menghapus Jabatan.');
            return redirect()->route('jabatan.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('kantor.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('kantor.index')->withStatus($e->getMessage());
        }
    }
}
