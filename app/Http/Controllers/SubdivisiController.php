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
        $data = DB::table('mst_sub_divisi')
            ->select(
                'mst_divisi.kd_divisi',
                'mst_sub_divisi.kd_subdiv',
                'mst_divisi.nama_divisi',
                'mst_sub_divisi.nama_subdivisi',
                'mst_sub_divisi.kd_divisi'
            )
            ->join('mst_divisi', 'mst_divisi.kd_divisi', '=', 'mst_sub_divisi.kd_divisi')
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
            'divisi' => 'required|not_in:-',
            'kd_subdiv' => 'required',
            'nama_subdivisi' => 'required'
        ], [
            'divisi.required' => 'Data harus diisi.',
            'divisi.not_in' => 'Data harus diisi.',
            'kd_subdiv.required' => 'Data harus diisi.',
            'nama_subdivisi.required' => 'Data harus diisi.'
        ]);

        try{
            if($request->divisi == '-'){
                Alert::error('Terjadi Kesalahan', 'Divisi harus diisi.');
                return redirect()->route('sub_divisi.index');
            }

            DB::table('mst_sub_divisi')
                ->insert([
                    'kd_divisi' => $request->get('divisi'),
                    'kd_subdiv' => $request->get('kd_subdiv'),
                    'nama_subdivisi' => $request->get('nama_subdivisi'),
                    'created_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Menambah Sub Divisi.');
            return redirect()->route('sub_divisi.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', 'Kode Sub Divisi Telah Digunakan');
            return redirect()->route('sub_divisi.index')->withStatus($e->getMessage());   
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Menambahkan Sub Divisi');
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
        $data = DB::table('mst_sub_divisi')
            ->where('mst_sub_divisi.kd_subdiv', $id)
            ->select(
                'mst_sub_divisi.kd_subdiv',
                'mst_divisi.nama_divisi',
                'mst_sub_divisi.nama_subdivisi',
                'mst_sub_divisi.kd_divisi'
            )
            ->join('mst_divisi', 'mst_divisi.kd_divisi', '=', 'mst_sub_divisi.kd_divisi')
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
            'divisi' => 'required|not_in:-',
            'kd_subdiv' => 'required',
            'nama_subdivisi' => 'required'
        ], [
            'divisi.required' => 'Data harus diisi.',
            'divisi.not_in' => 'Data harus diisi.',
            'kd_subdiv.required' => 'Data harus diisi.',
            'nama_subdivisi.required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_sub_divisi')
                ->where('kd_subdiv', $id)
                ->update([
                    'kd_divisi' => $request->get('divisi'),
                    'kd_subdiv' => $request->get('kd_subdiv'),
                    'nama_subdivisi' => $request->get('nama_subdivisi'),
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Mengupdate Sub Divisi.');
            return redirect()->route('sub_divisi.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', 'Kode Sub Divisi Telah Digunakan.'.$e);
            return redirect()->route('sub_divisi.index')->withStatus($e->getMessage());   
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Mengupdate Sub Divisi'.$e);
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
            DB::table('mst_sub_divisi')
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
