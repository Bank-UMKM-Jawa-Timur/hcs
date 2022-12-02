<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TunjanganController extends Controller
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
        $data = DB::table('mst_tunjangan')
            ->get();

        return view('tunjangan.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tunjangan.add');
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
            'nama_tunjangan' => 'required'
        ], [
            'required' => 'Data harus isi.'
        ]);

        try{
            DB::table('mst_tunjangan')
                ->insert([
                    'nama_tunjangan' => $request->get('nama_tunjangan'),
                    'created_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Menambah Tunjangan.');
            return redirect()->route('tunjangan.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('tunjangan.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Tunjangan.');
            return redirect()->route('tunjangan.index')->withStatus($e->getMessage());
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
        $data = DB::table('mst_tunjangan')
            ->where('id', $id)
            ->first();

        return view('tunjangan.edit', ['data' => $data]);
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
            'nama_tunjangan' => 'required'
        ], [
            'required' => 'Data harus isi.'
        ]);

        try{
            DB::table('mst_tunjangan')
                ->where('id', $id)
                ->update([
                    'nama_tunjangan' => $request->get('nama_tunjangan'),
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Mengupdate Tunjangan.');
            return redirect()->route('tunjangan.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('tunjangan.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Mengupdate Tunjangan');
            return redirect()->route('tunjangan.index')->withStatus($e->getMessage());
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
        try{
            DB::table('mst_tunjangan')
                ->where('id', $id)
                ->delete();

            Alert::success('Berhasil', 'Berhasil Menghapus Tunjangan.');
            return redirect()->route('tunjangan.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('tunjangan.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Menghapus Tunjangan');
            return redirect()->route('tunjangan.index')->withStatus($e->getMessage());
        }
    }
}
