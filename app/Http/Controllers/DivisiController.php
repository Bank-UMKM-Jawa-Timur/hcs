<?php

namespace App\Http\Controllers;

use App\Models\DivisiModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = DivisiModel::select()->get();

        return view('divisi.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('divisi.add');
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
            'nama_divisi' => 'required'
        ],[
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_divisi')
                ->insert([
                    'nama_divisi' => $request->get('nama_divisi'),
                    'id_kantor' => 1,
                    'created_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil menambah data');
            return redirect()->route('divisi.index');
        }catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('divisi.index');
        }catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('divisi.index');
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
        $data = DivisiModel::where('id', $id)
            ->first();

        return view('divisi.edit', ['data' => $data]);
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
            'nama_divisi' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_divisi')
                ->where('id' ,$id)
                ->update([
                    'nama_divisi' => $request->get('nama_divisi'),
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil mengupdate divisi.');
            return redirect()->route('divisi.index');
        }catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('divisi.index');
        }catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('divisi.index');
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
            DB::table('mst_divisi')
            ->where('id', $id)
            ->delete();

            Alert::success('Berhasil', 'Berhasil menghapus divisi.');
            return redirect()->route('divisi.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('divisi.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('divisi.index');
        }
    }
}
