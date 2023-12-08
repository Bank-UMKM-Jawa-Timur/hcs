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
        $data = KantorModel::select(
            'id',
            'nama_kantor'
        )->get();
        return view('kantor.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
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
            DB::table('mst_kantor')
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
        // Need permission
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
            DB::table('mst_kantor')
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
        // Need permission
        try{
            DB::table('mst_kantor')
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
