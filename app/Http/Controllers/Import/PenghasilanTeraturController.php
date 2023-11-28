<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\KaryawanModel;
use App\Models\TunjanganModel;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;


class PenghasilanTeraturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $penghasilan = TunjanganModel::where('kategori', 'teratur')->where('is_import', 1)->get();
        return view('penghasilan-teratur.create', compact('penghasilan'));
    }

    public function getKaryawanByEntitas($nip){
        $data = KaryawanModel::where('nip', $nip)->get();

        if (count($data) > 0) {
            return response()->json([
                'status' => 'Success',
                'message' => 'success',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'Success',
                'message' => 'data not found',
                'data' => 'null'
            ]);
        }
    }
    public function getKaryawanSearch(){
        $data = KaryawanModel::select('nip as value', 'nama_karyawan')->get();

        if (count($data) > 0) {
            return response()->json([
                'status' => 'Success',
                'message' => 'success',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'Success',
                'message' => 'data not found',
                'data' => 'null'
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $total = $request->get('number');
            $id_tunjangan = $request->get('penghasilan');
            $nip = $request->get('nip');
            $nominal = str_replace([' ', '.', "\u{A0}"], '', $request->get('nominal'));

            if ($total) {
                if (is_array($total)) {
                    for ($i=0; $i < count($total); $i++) {
                        DB::table('tunjangan_karyawan')->insert([
                            'nip' => $nip[$i],
                            'nominal' => $nominal[$i],
                            'id_tunjangan' => $id_tunjangan[$i]
                        ]);
                    }
                }
            }

            Alert::success('Success', 'Berhasil menyimpan data');
            return redirect()->route('penghasilan.import-penghasilan-teratur.index');

        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            Alert::error('Error', $e->getMessage());
            return back();
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
