<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Models\TunjanganModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BonusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bonus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tunjangan = TunjanganModel::select('nama_tunjangan','id')->where('kategori','bonus')->get();
        return view('bonus.import',[
            'data_tunjangan' => $tunjangan
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
        $request->validate([
            'upload_csv' => 'required|mimes:xlsx,xls',
            'nip.*' => 'required',
            'kategori_tunjangan' => 'required',
            'kategori.*' => 'required',
            'nominal.*' => 'required',
        ],[
            'kategori.*' => ':attribute harus terisi.'
        ],[
            'kategori.*' => 'Kategori',
            'nip.*' => 'NIP'
        ]);
        try {
            \DB::beginTransaction();
            if ($request->get('kategori_bonus') == 'penghasilan-lainnya') {
                $tunjangan = TunjanganModel::where('id',$request->get('kategori_tunjangan'))->first();
                for ($i=0; $i < count($request->get('nip')); $i++) {
                    $data = KaryawanModel::select('nip')->where('nip', $_POST['nip'][$i])->first()->nip ?? null;
                    if ($data) {
                        DB::table('penghasilan_tidak_teratur')
                        ->insert([
                            'nip' => $data,
                            'id_tunjangan' => $request->get('kategori_tunjangan'),
                            'nominal' => $_POST['nominal'][$i],
                            'bulan' => Carbon::now()->format('m'),
                            'tahun' => Carbon::now()->format('Y'),
                            'created_at' => now()
                        ]);
                    }
                }
                \DB::commit();
            }
            Alert::success('Berhasil', 'Berhasil menambahkan data penghasilan tambahan');
            return redirect()->route('bonus.index');
        } catch (Exception $th) {
            \DB::rollBack();
            return $th;
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