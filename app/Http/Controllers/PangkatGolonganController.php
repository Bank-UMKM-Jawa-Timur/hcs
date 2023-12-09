<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PangkatGolonganController extends Controller
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
        $data = DB::table('mst_pangkat_golongan')
            ->get();

        return view('pangkat_golongan.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
        return view('pangkat_golongan.add');
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
            'pangkat' => 'required',
            'golongan' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_pangkat_golongan')
                ->insert([
                    'pangkat' => $request->get('pangkat'),
                    'golongan' => $request->get('golongan'),
                    'created_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Menambah Pangkat dan Golongan.');
            return redirect()->route('pangkat_golongan.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', 'Golongan Telah Digunakan');
            return redirect()->route('pangkat_golongan.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Pangkat dan Golongan.');
            return redirect()->route('pangkat_golongan.index')->withStatus($e->getMessage());
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
        $data = DB::table('mst_pangkat_golongan')
            ->where('golongan', $id)
            ->first();

        return view('pangkat_golongan.edit', ['data' => $data]);
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
            'pangkat' => 'required',
            'golongan' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_pangkat_golongan')
                ->where('golongan', $id)
                ->update([
                    'pangkat' => $request->get('pangkat'),
                    'golongan' => $request->get('golongan'),
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Mengupdate Pangkat dan Golongan.');
            return redirect()->route('pangkat_golongan.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', 'Golongan Telah Digunakan.');
            return redirect()->route('pangkat_golongan.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Mengupdate Pangkat dan Golongan.');
            return redirect()->route('pangkat_golongan.index')->withStatus($e->getMessage());
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
            DB::table('mst_pangkat_golongan')
                ->where('golongan', $id)
                ->delete();

            Alert::success('Berhasil', 'Berhasil menghapus pangkat dan golongan.');
            return redirect()->route('pangkat_golongan.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('pangkat_golongan.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('pangkat_golongan.index')->withStatus($e->getMessage());
        }
    }
}
