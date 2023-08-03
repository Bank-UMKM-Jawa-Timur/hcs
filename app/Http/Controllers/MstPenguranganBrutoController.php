<?php

namespace App\Http\Controllers;

use App\Models\MstProfilKantorModel;
use App\Models\PemotongPajakPenguranganModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MstPenguranganBrutoController extends Controller
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
    public function index(Request $request)
    {
        $data = PemotongPajakPenguranganModel::where('pemotong_pajak_pengurangan.id_profil_kantor', $request->profil_kantor)
                                        ->select(
                                            'pemotong_pajak_pengurangan.*',
                                            'c.nama_cabang'
                                        )
                                        ->join('mst_profil_kantor AS p', 'p.id', 'pemotong_pajak_pengurangan.id_profil_kantor')
                                        ->join('mst_cabang AS c', 'c.kd_cabang', 'p.kd_cabang')
                                        ->orderBy('active', 'DESC')
                                        ->orderBy('id', 'DESC')
                                        ->get();

        return view('pengurangan-bruto.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $kd_cabang = '';
        $kantor = MstProfilKantorModel::select('kd_cabang')->where('id', $request->profil_kantor)->first();
        if ($kantor)
            $kd_cabang = $kantor->kd_cabang;

        return view('pengurangan-bruto.add', ['kd_cabang'=>$kd_cabang]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = route('pengurangan-bruto.index').'?profil_kantor='.$request->id_profil_kantor;
        try{
            $this->validate($request, [
                'dpp' => 'required',
                'jp' => 'required',
                'jp_jan_feb' => 'required',
                'jp_mar_des' => 'required',
            ], [
                'required' => ':attribute harus diisi.',
                'decimal' => ':attribute harus berupa angka.',
            ], [
                'dpp' => 'DPP',
                'jp' => 'JP',
                'jp_jan_feb' => 'JP Januari - Februari',
                'jp_mar_des' => 'JP Maret - Desember',
            ]);

            $update = false;
            $checkCurrent = PemotongPajakPenguranganModel::select('id')->where('id_profil_kantor', $request->id_profil_kantor)->count();
            if ($checkCurrent > 0) {
                PemotongPajakPenguranganModel::where('id_profil_kantor', $request->id_profil_kantor)->update(['active'=>0]);
            }

            $createPemotong = new PemotongPajakPenguranganModel;
            $createPemotong->id_profil_kantor = $request->id_profil_kantor;
            $createPemotong->dpp = $request->dpp;
            $createPemotong->jp = $request->jp;
            $createPemotong->jp_jan_feb = str_replace('.', "", $request->jp_jan_feb);
            $createPemotong->jp_mar_des = str_replace('.', "", $request->jp_mar_des);
            $createPemotong->save();

            Alert::success('Berhasil', 'Berhasil menyimpan data.');

            return redirect($url);
        } catch(Exception $e){
            return $e->getMessage();
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return redirect($url)->withStatus($e->getMessage());
        } catch(QueryException $e){
            return $e->getMessage();
            Alert::error('Terjadi kesalahan pada database', $e->getMessage());
            return redirect($url)->withStatus($e->getMessage());
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
        $data = PemotongPajakPenguranganModel::find($id);
        $kd_cabang = '';
        $kantor = MstProfilKantorModel::select('kd_cabang')->where('id', $data->id_profil_kantor)->first();
        if ($kantor)
            $kd_cabang = $kantor->kd_cabang;

        $data = [
            'kd_cabang' => $kd_cabang,
            'data' => $data,
        ];
        return view('pengurangan-bruto.edit', $data);
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
        $url = route('pengurangan-bruto.index').'?profil_kantor='.$request->id_profil_kantor;
        try{
            $this->validate($request, [
                'dpp' => 'required',
                'jp' => 'required',
                'jp_jan_feb' => 'required',
                'jp_mar_des' => 'required',
            ], [
                'required' => ':attribute harus diisi.',
                'decimal' => ':attribute harus berupa angka.',
            ], [
                'dpp' => 'DPP',
                'jp' => 'JP',
                'jp_jan_feb' => 'JP Januari - Februari',
                'jp_mar_des' => 'JP Maret - Desember',
            ]);

            $currentPemotong = PemotongPajakPenguranganModel::find($id);
            $currentPemotong->id_profil_kantor = $request->id_profil_kantor;
            $currentPemotong->dpp = $request->dpp;
            $currentPemotong->jp = $request->jp;
            $currentPemotong->jp_jan_feb = $request->jp_jan_feb;
            $currentPemotong->jp_mar_des = $request->jp_mar_des;
            $currentPemotong->save();

            Alert::success('Berhasil', 'Berhasil menyimpan data.');
            return redirect($url);
        } catch(Exception $e){
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return redirect($url)->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi kesalahan pada database', $e->getMessage());
            return redirect($url)->withStatus($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try{
            $url = route('pengurangan-bruto.index').'?profil_kantor='.$request->id_profil_kantor;

            PemotongPajakPenguranganModel::findOrFail($id)->delete();

            Alert::success('Berhasil', 'Berhasil menghapus data.');
            return redirect($url);
        } catch(Exception $e){
            Alert::error('Terjadi kesalahan', ''.$e->getMessage());
            return redirect()->route('pengurangan-bruto.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi kesalahan pada database', ''.$e->getMessage());
            return redirect()->route('pengurangan-bruto.index')->withStatus($e->getMessage());
        }
    }
}
