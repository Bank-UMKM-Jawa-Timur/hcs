<?php

namespace App\Http\Controllers;

use App\Models\MstProfilKantorModel;
use App\Models\PemotongPajakTambahanModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MstPenambahanBrutoController extends Controller
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
        $data = PemotongPajakTambahanModel::where('pemotong_pajak_tambahan.id_profil_kantor', $request->profil_kantor)
                                        ->select(
                                            'pemotong_pajak_tambahan.*',
                                            'c.nama_cabang'
                                        )
                                        ->join('mst_profil_kantor AS p', 'p.id', 'pemotong_pajak_tambahan.id_profil_kantor')
                                        ->join('mst_cabang AS c', 'c.kd_cabang', 'p.kd_cabang')
                                        ->orderBy('active', 'DESC')
                                        ->orderBy('id', 'DESC')
                                        ->get();

        return view('penambahan-bruto.index', ['data' => $data]);
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

        return view('penambahan-bruto.add', ['kd_cabang'=>$kd_cabang]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = route('penambahan-bruto.index').'?profil_kantor='.$request->id_profil_kantor;
        try{
            $this->validate($request, [
                'jkk' => 'required',
                'jht' => 'required',
                'jkm' => 'required',
                'kesehatan' => 'required',
                'kesehatan_batas_atas' => 'required',
                'kesehatan_batas_bawah' => 'required',
                'jp' => 'required',
            ], [
                'required' => ':attribute harus diisi.',
                'decimal' => ':attribute harus berupa angka.',
            ], [
                'jkk' => 'JKK',
                'jht' => 'JHT',
                'jkm' => 'JKM',
                'kesehatan' => 'Kesehatan',
                'kesehatan_batas_atas' => 'Batas Atas',
                'kesehatan_batas_bawah' => 'Batas Bawah',
                'jp' => 'required|decimal:4,2',
            ]);

            $update = false;
            $checkCurrent = PemotongPajakTambahanModel::select('id')->where('id_profil_kantor', $request->id_profil_kantor)->count();
            if ($checkCurrent > 0) {
                PemotongPajakTambahanModel::where('id_profil_kantor', $request->id_profil_kantor)->update(['active'=>0]);
            }

            $createPemotong = new PemotongPajakTambahanModel;
            $createPemotong->id_profil_kantor = $request->id_profil_kantor;
            $createPemotong->jkk = $request->jkk;
            $createPemotong->jht = $request->jht;
            $createPemotong->jkm = $request->jkm;
            $createPemotong->kesehatan = $request->kesehatan;
            $createPemotong->kesehatan_batas_atas = $request->kesehatan_batas_atas;
            $createPemotong->kesehatan_batas_bawah = $request->kesehatan_batas_bawah;
            $createPemotong->jp = $request->jp;
            $createPemotong->total = $request->total;
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
        $data = PemotongPajakTambahanModel::find($id);
        $kd_cabang = '';
        $kantor = MstProfilKantorModel::select('kd_cabang')->where('id', $data->id_profil_kantor)->first();
        if ($kantor)
            $kd_cabang = $kantor->kd_cabang;

        $data = [
            'kd_cabang' => $kd_cabang,
            'data' => $data,
        ];
        return view('penambahan-bruto.edit', $data);
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
        $url = route('penambahan-bruto.index').'?profil_kantor='.$request->id_profil_kantor;
        try{
            $this->validate($request, [
                'jkk' => 'required',
                'jht' => 'required',
                'jkm' => 'required',
                'kesehatan' => 'required',
                'kesehatan_batas_atas' => 'required',
                'kesehatan_batas_bawah' => 'required',
                'jp' => 'required',
            ], [
                'required' => ':attribute harus diisi.',
                'decimal' => ':attribute harus berupa angka.',
            ], [
                'jkk' => 'JKK',
                'jht' => 'JHT',
                'jkm' => 'JKM',
                'kesehatan' => 'Kesehatan',
                'kesehatan_batas_atas' => 'Batas Atas',
                'kesehatan_batas_bawah' => 'Batas Bawah',
                'jp' => 'required|decimal:4,2',
            ]);

            $currentPemotong = PemotongPajakTambahanModel::find($id);
            $currentPemotong->id_profil_kantor = $request->id_profil_kantor;
            $currentPemotong->jkk = $request->jkk;
            $currentPemotong->jht = $request->jht;
            $currentPemotong->jkm = $request->jkm;
            $currentPemotong->kesehatan = $request->kesehatan;
            $currentPemotong->kesehatan_batas_atas = $request->kesehatan_batas_atas;
            $currentPemotong->kesehatan_batas_bawah = $request->kesehatan_batas_bawah;
            $currentPemotong->jp = $request->jp;
            $currentPemotong->total = $request->total;
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
            $url = route('penambahan-bruto.index').'?profil_kantor='.$request->id_profil_kantor;

            PemotongPajakTambahanModel::findOrFail($id)->delete();

            Alert::success('Berhasil', 'Berhasil menghapus data.');
            return redirect($url);
        } catch(Exception $e){
            Alert::error('Terjadi kesalahan', ''.$e->getMessage());
            return redirect()->route('penambahan-bruto.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi kesalahan pada database', ''.$e->getMessage());
            return redirect()->route('penambahan-bruto.index')->withStatus($e->getMessage());
        }
    }
}
