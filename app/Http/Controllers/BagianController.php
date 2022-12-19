<?php

namespace App\Http\Controllers;

use App\Service\EntityService;
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
        $kd_ent = null;

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
        $request->validate([
            'kantor' => 'required|not_in:-',
            'kd_bagian' => 'required',
            'nama_bagian' => 'required',
        ], [
            'kantor.required' => 'Data harus diisi.',
            'kantor.not_in' => 'Data harus diisi.',
            'kd_bagian.required' => 'Data harus diisi.',
            'nama.required' => 'Data harus diisi.',
        ]);

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

            Alert::success('Berhasil', 'Berhasil Menambah Bagian.');
            return redirect()->route('bagian.index');
            } catch(Exception $e){
                DB::rollBack();
                Alert::error('Terjadi Kesalahan', ''.$e->getMessage());
                return redirect()->route('bagian.index');
            } catch(QueryException $e){
                DB::rollBack();
                Alert::error('Terjadi Kesalahan', 'Gagal Menambah Bagian.'.$e->getMessage());
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
        $entity = EntityService::getEntity($data->kd_entitas);

        return view('bagian.edit', compact('data', 'entity'));
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
            'kantor' => 'required|not_in:-',
            'kd_bagian' => 'required',
            'nama_bagian' => 'required',
        ], [
            'kantor.required' => 'Data harus diisi.',
            'kantor.not_in' => 'Data harus diisi.',
            'kd_bagian.required' => 'Data harus diisi.',
            'nama.required' => 'Data harus diisi.',
        ]);

        try {
            if ($request->get('kd_subdiv') != null) {
                $kd_entitas = $request->get('kd_subdiv');
                $request->validate([
                    'kd_subdiv' => 'required|exists:mst_sub_divisi,kd_subdiv'
                ]);
            } else if ($request->get('kd_cabang') != null) {
                $kd_entitas = $request->get('kd_cabang');
                $request->validate([
                    'kd_cabang' => 'required|exists:mst_cabang,kd_cabang'
                ]);
            } else {
                $kd_entitas = $request->get('kd_divisi');
                $request->validate([
                    'kd_divisi' => 'required|exists:mst_divisi,kd_divisi'
                ]);
            }

            DB::table('mst_bagian')
                ->where('kd_bagian', $id)
                ->update([
                    'kd_bagian' => $request->get('kd_bagian'),
                    'nama_bagian' => $request->get('nama_bagian'),
                    'kd_entitas' => $kd_entitas,
                    'updated_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Mengupdate Bagian.');
            return redirect()->route('bagian.index');
        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e->getMessage());
            return redirect()->route('bagian.index');
        } catch (QueryException $e) {
            DB::rollBack();
            Alert::error('Terjadi Kesahalan', 'Gagal Mengupdate Bagian.'.$e->getMessage());
            redirect()->route('bagian.index');
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
