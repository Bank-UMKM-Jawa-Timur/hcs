<?php

namespace App\Http\Controllers;

use App\Models\DivisiModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SubdivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('sub_divisi')
            ->select(
                'sub_divisi.id',
                'divisi.nama_divisi',
                'sub_divisi.nama_subdivisi',
                'sub_divisi.id_divisi'
            )
            ->join('divisi', 'divisi.id', '=', 'sub_divisi.id_divisi')
            ->get();

            // dd($data);

        return view('sub_divisi.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $divisi = DivisiModel::get();

        return view('sub_divisi.add', ['divisi' => $divisi]);
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
            'divisi' => 'required',
            'nama_subdivisi' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            if($request->divisi == '-'){
                Alert::error('Terjadi Kesalahan', 'Divisi harus diisi.');
                return redirect()->route('sub_divisi.index');
            }

            DB::table('sub_divisi')
                ->insert([
                    'id_divisi' => $request->get('divisi'),
                    'nama_subdivisi' => $request->get('nama_subdivisi'),
                    'created_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil menambahkan sub divisi.');
            return redirect()->route('sub_divisi.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('sub_divisi.index')->withStatus($e->getMessage());   
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('sub_divisi.index')->withStatus($e->getMessage());   
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
        $data = DB::table('sub_divisi')
            ->where('sub_divisi.id', $id)
            ->select(
                'sub_divisi.id',
                'divisi.nama_divisi',
                'sub_divisi.nama_subdivisi',
                'sub_divisi.id_divisi'
            )
            ->join('divisi', 'divisi.id', '=', 'sub_divisi.id_divisi')
            ->first();
            
        $divisi = DivisiModel::get();

        return view('sub_divisi.edit', ['data' => $data, 'divisi' => $divisi]);
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
            'divisi' => 'required',
            'nama_subdivisi' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('sub_divisi')
                ->where('id', $id)
                ->update([
                    'id_divisi' => $request->get('divisi'),
                    'nama_subdivisi' => $request->get('nama_subdivisi'),
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil mengupdate data.');
            return redirect()->route('sub_divisi.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('sub_divisi.index')->withStatus($e->getMessage());   
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('sub_divisi.index')->withStatus($e->getMessage());   
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
            DB::table('sub_divisi')
                ->where('id', $id)
                ->delete();
    
            Alert::success('Berhasil', 'Berhasil menghapus sub divisi.');
            return redirect()->route('sub_divisi.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('sub_divisi.index')->withStatus($e->getMessage());
        }catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('sub_divisi.index')->withStatus($e->getMessage());       
        }
    }
}
