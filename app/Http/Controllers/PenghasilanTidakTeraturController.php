<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PenghasilanTidakTeraturController extends Controller
{
    public function getDataKaryawan(Request $request)
    {
        $nip = $request->get('nip');
        $karyawan = DB::table('mst_karyawan')
            ->where('nip', $nip)
            ->first();
        return response()->json($karyawan);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('penghasilan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tj = DB::table('mst_tunjangan')
            ->whereIn('id', [11, 12, 13, 14])
            ->get();
        $tidak_teratur = DB::table('mst_tunjangan')
            ->whereIn('id', [16, 17, 18, 19, 20, 21])
            ->get();
        $bonus = DB::table('mst_tunjangan')
            ->whereIn('id', [22, 23, 24])
            ->get();
        
        return view('penghasilan.add', [
            'tj' => $tj,
            'tidak_teratur' => $tidak_teratur,
            'bonus' => $bonus
        ]);
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
        $nip = $request->nip;
        try{
            if($request->get('nominal_teratur')[0] != null){
                for($i = 0; $i < count($request->get('nominal_teratur')); $i++){
                    DB::table('penghasilan_tidak_teratur')
                        ->insert([
                            'nip' => $nip,
                            'id_tunjangan' => $request->get('id_teratur')[$i],
                            'nominal' => $request->get('nominal_teratur')[$i],
                            'bulan' => $request->get('bulan'),
                            'tahun' => $request->get('bulan'),
                            'created_at' => now()
                        ]);
                }
            }
            if($request->get('nominal_tidak_teratur')[0] != null){
                for($i = 0; $i < count($request->get('nominal_tidak_teratur')); $i++){
                    DB::table('penghasilan_tidak_teratur')
                        ->insert([
                            'nip' => $nip,
                            'id_tunjangan' => $request->get('id_tidak_teratur')[$i],
                            'nominal' => $request->get('nominal_tidak_teratur')[$i],
                            'bulan' => $request->get('bulan'),
                            'tahun' => $request->get('bulan'),
                            'created_at' => now()
                        ]);
                }
            }
            if($request->get('nominal_bonus')[0] != null){
                for($i = 0; $i < count($request->get('nominal_bonus')); $i++){
                    DB::table('penghasilan_tidak_teratur')
                        ->insert([
                            'nip' => $nip,
                            'id_tunjangan' => $request->get('id_bonus')[$i],
                            'nominal' => $request->get('nominal_bonus')[$i],
                            'bulan' => $request->get('bulan'),
                            'tahun' => $request->get('bulan'),
                            'created_at' => now()
                        ]);
                }
            }

            Alert::success('Berhasil', 'Berhasil menambahkan data penghasilan');
            return redirect()->route('penghasilan-tidak-teratur.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('penghasilan-tidak-teratur.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('penghasilan-tidak-teratur.index');
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
