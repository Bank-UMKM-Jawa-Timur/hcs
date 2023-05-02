<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class GajiPerBulanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getBulan(Request $request)
    {
        $tahun = $request->get('tahun');

        $bulan = DB::table('gaji_per_bulan')
            ->where('tahun', $tahun)
            ->distinct()
            ->get('bulan');
        if(count($bulan) > 0){
            return response()->json($bulan);
        } else{
            return null;
        }
    }

    public function index()
    {
        $data = DB::table('gaji_per_bulan')
            ->select('bulan', 'tahun')
            ->orderBy('created_at', 'desc')
            ->groupBy('tahun')
            ->get();
        return view('gaji_perbulan.index', ['data_gaji' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $tunjangan = array();
            $karyawan = DB::table('mst_karyawan')
                ->get();

            foreach($karyawan as $item){
                unset($tunjangan);
                $tunjangan = array();
                for($i = 1; $i <= 15; $i++){
                    $tj = DB::table('tunjangan_karyawan')
                        ->where('nip', $item->nip)
                        ->where('id_tunjangan', $i)
                        ->first();
                    array_push($tunjangan, ($tj != null) ? $tj->nominal : 0);
                }

                DB::table('gaji_per_bulan')
                    ->insert([
                        'nip' => $item->nip,
                        'bulan' => $request->get('bulan'),
                        'tahun' => $request->get('tahun'),
                        'gj_pokok' => $item->gj_pokok,
                        'gj_penyesuaian' => $item->gj_penyesuaian,
                        'tj_keluarga' => $tunjangan[0],
                        'tj_telepon' => $tunjangan[1],
                        'tj_jabatan' => $tunjangan[2],
                        'tj_teller' => $tunjangan[3],
                        'tj_perumahan' => $tunjangan[4],
                        'tj_kemahalan' => $tunjangan[5],
                        'tj_pelaksana' => $tunjangan[6],
                        'tj_kesejahteraan' => $tunjangan[7],
                        'tj_multilevel' => $tunjangan[8],
                        'tj_ti' => $tunjangan[9],
                        'tj_transport' => $tunjangan[10],
                        'tj_pulsa' => $tunjangan[11],
                        'tj_vitamin' => $tunjangan[12],
                        'uang_makan' => $tunjangan[13],
                        'dpp' => $tunjangan[14],
                        'created_at' => now()
                    ]);
                }
                Alert::success('Berhasil', 'Berhasil Melakukan Pembayaran Gaji Karyawan.');
                return redirect()->route('gaji_perbulan.index');
        }catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e);
            return redirect()->route('gaji_perbulan.index');
        }catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e);
            return redirect()->route('gaji_perbulan.index');
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
