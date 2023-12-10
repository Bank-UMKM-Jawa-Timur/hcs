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
        return view('bonus.import');
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
            'kategori.*' => 'required',
            'nominal.*' => 'required',
        ],[
            'kategori.*' => ':attribute harus terisi.'
        ],[
            'kategori.*' => 'Kategori'
        ]);
        try {
            \DB::beginTransaction();
            if ($request->get('kategori_bonus') == 'penghasilan-lainnya') {
                $tunjangan = TunjanganModel::where('nama_tunjangan','Tambahan Penghasilan')->where('kategori','bonus')->first();
                for ($i=0; $i < count($request->get('nip')); $i++) {
                    $data = KaryawanModel::select('nip')->where('nip', $_POST['nip'][$i])->first()->nip ?? null;
                    if ($data) {
                        DB::table('penghasilan_tidak_teratur')
                        ->insert([
                            'nip' => $data,
                            'id_tunjangan' => $tunjangan->id,
                            'nominal' => $_POST['nominal'][$i],
                            'ket' => $_POST['kategori'][$i],
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
