<?php

namespace App\Http\Controllers;

use App\Models\KantorModel;
use Exception;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class KantorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = KantorModel::select(
            'id',
            'nama_kantor'
        )->paginate(5);
        return view('kantor.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kantor.add');
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
            'nama_kantor' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('kantor')
                ->insert([
                    'nama_kantor' => $request->get('nama_kantor'),
                    'created_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil menambahkan kantor.');
            return redirect()->route('kantor.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('kantor.index')->withStatus($e->getMessage());            
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
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
        //
        $dataKantor = KantorModel::where('id', $id)
            ->first();

        return view('kantor.edit', compact('dataKantor'));
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
            'nama_kantor' => 'required'
        ], [
            'required' => 'Data Harus Diisi!'
        ]);

        try{
            DB::table('kantor')
                ->where('id', $id)
                ->update([
                    'nama_kantor' => $request->get('nama_kantor'),
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil mengupdate kantor.');
            return redirect()->route('kantor.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('kantor.index')->withStatus($e->getMessage());            
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
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
        try{
            DB::table('kantor')
                ->where('id', $id)
                ->delete();
    
            Alert::success('Berhasil', 'Berhasil menghapus kantor.');
            return redirect()->route('kantor.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('kantor.index')->withStatus($e->getMessage());
        }catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('kantor.index')->withStatus($e->getMessage());       
        }
    }
}
