<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BagianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('mst_bagian')
            ->get();

        return view('bagian.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = DB::table('mst_kantor')
            ->get();

        return view('bagian.add', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if($request->get('kd_subdiv') != null){
                $kd_entitas = $request->get('kd_subdiv');
            } else if($request->get('kd_cabang') != null){
                $kd_entitas = $request->get('kd_cabang');
            } else{
                $kd_entitas = $request->get('kd_divisi');
            }

            DB::table('mst_bagian')
                ->insert([
                    'kd_bagian' => $request->get('kd_bagian'),
                    'nama_bagian' => $request->get('nama_bagian'),
                    'kd_entitas' => $kd_entitas,
                    'created_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil melakukan bagian.');
            return redirect()->route('bagian.index');
            } catch(Exception $e){
                DB::rollBack();
                Alert::error('Terjadi Kesalahan', $e->getMessage());
                return redirect()->route('bagian.index');
            } catch(QueryException $e){
                DB::rollBack();
                Alert::error('Terjadi Kesalahan', $e->getMessage());
                return redirect()->route('bagian.index');
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
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DB::table('mst_bagian')
            ->where('kd_bagian', $id)
            ->first();
        $data1 = DB::table('mst_divisi')
            ->where('kd_divisi', $data->kd_entitas)
            ->first();
        $data2 = DB::table('mst_sub_divisi')
            ->where('kd_subdiv', $data->kd_entitas)
            ->first();
        $data3 = DB::table('mst_cabang')
            ->where('kd_cabang', $data->kd_entitas)
            ->first();
        if(isset($data1)){
            $data = DB::table('mst_bagian, mst.divisi')
            ->join('mst_divisi', 'mst_divisi.kd_divisi', '=', 'mst_bagian.kd_bagian')
                ->join('mst_kantor', 'mst_kantor.id', '=', 'mst_divisi.id_kantor')
                ->first();
        }else if(isset($data2)){
            $data = DB::table('mst_bagian')
                ->join('mst_sub_divisi', 'mst_sub_divisi.kd_subdiv', '=', 'mst_bagian.kd_entitas')
                ->join('mst_divisi', 'mst_divisi.kd_divisi', '=', 'mst_sub_divisi.kd_divisi')
                ->join('mst_kantor', 'mst_kantor.id', '=', 'mst_divisi.id_kantor')
                ->first();
        }else if(isset($data3)){
            $data = DB::table('mst_bagian, mst.cabang')
                ->join('mst_kantor', 'mst_kantor.id', '=', 'mst_divisi.id_kantor')
                ->first();
        }
        $data->cabang = DB::table('mst_cabang')
            ->get();
        $data->divisi = DB::table('mst_divisi')
            ->get();
        dd($data);
        return view('bagian.edit', ['data' => $data]);
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
