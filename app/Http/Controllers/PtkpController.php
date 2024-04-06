<?php

namespace App\Http\Controllers;

use App\Models\PtkpModel;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\QueryException;
use Exception;
use App\Helpers\FormatUang;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Auth;

class PtkpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Need permission
        $data = PtkpModel::all();
        return view('ptkp.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
        return view('ptkp.add');
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
            'kode' => 'required',
            'ptkp_tahun' => 'required',
            'ptkp_bulan' => 'required',
            'keterangan' => 'required'
        ],[
            'kode.required' => 'Kode Harus diisi',
            'ptkp_tahun.required' => 'PTKP per tahun Harus diisi',
            'ptkp_bulan.required' => 'PTKP perbulan Harus diisi',
            'keterangan.required' => 'keterangan Harus diisi',
        ]);

        try{
            $tahun = FormatUang::hapusFormat(Request()->ptkp_tahun);
            $bulan = FormatUang::hapusFormat(Request()->ptkp_bulan);


            $data = [
                'kode' => $request->kode,
                'ptkp_tahun' => $tahun,
                'ptkp_bulan' => $bulan,
                'keterangan' => $request->keterangan,
            ];
            PtkpModel::create($data);

            // Record to log activity
            $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
            $activity = "Pengguna <b>$name</b> menambah PTKP dengan kode <b>$request->kode($request->keterangan)</b>, PTKP per tahun <b>Rp. $request->ptkp_tahun</b>, PTKP per bulan <b>Rp. $request->ptkp_bulan</b>";
            LogActivity::create($activity);

            Alert::success('Berhasil', 'Berhasil Menambah Penghasilan Tanpa Pajak.');
            return redirect()->route('ptkp.index');
        } catch (Exception $e) {
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Penghasilan Tanpa Pajak.');
            return redirect()->route('ptkp.index')->withStatus($e->getMessage());
        } catch (QueryException $e) {
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Penghasilan Tanpa Pajak.');
            return redirect()->route('ptkp.index')->withStatus($e->getMessage());
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
        // Need permission
        $data = PtkpModel::where('id', $id)->first();
        return view('ptkp.edit', compact('data'));
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
            'kode' => 'required',
            'ptkp_tahun' => 'required',
            'ptkp_bulan' => 'required',
            'keterangan' => 'required'
        ], [
            'kode.required' => 'Kode Harus diisi',
            'ptkp_tahun.required' => 'PTKP per tahun Harus diisi',
            'ptkp_bulan.required' => 'PTKP perbulan Harus diisi',
            'keterangan.required' => 'keterangan Harus diisi',
        ]);

        try {
            $tahun = FormatUang::hapusFormat(Request()->ptkp_tahun);
            $bulan = FormatUang::hapusFormat(Request()->ptkp_bulan);


            $data = [
                'kode' => $request->kode,
                'ptkp_tahun' => $tahun,
                'ptkp_bulan' => $bulan,
                'keterangan' => $request->keterangan,
                'updated_at' => now()
            ];
            PtkpModel::where('id', $id)->update($data);

            // Record to log activity
            $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
            $activity = "Pengguna <b>$name</b> melakukan update PTKP dengan kode <b>$request->kode($request->keterangan)</b>, PTKP per tahun <b>Rp. $request->ptkp_tahun</b>, PTKP per bulan <b>Rp. $request->ptkp_bulan</b>";
            LogActivity::create($activity);

            Alert::success('Berhasil', 'Berhasil Edit Penghasilan Tanpa Pajak.');
            return redirect()->route('ptkp.index');
        } catch (Exception $e) {
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Penghasilan Tanpa Pajak.');
            return redirect()->route('ptkp.index')->withStatus($e->getMessage());
        } catch (QueryException $e) {
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Penghasilan Tanpa Pajak.');
            return redirect()->route('ptkp.index')->withStatus($e->getMessage());
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
        // Need permission
        try {
            $data = PtkpModel::find($id);
            if ($data) {
                $data->delete();
                $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
                $activity = "Pengguna <b>$name</b> menghapus PTKP dengan kode <b>$data->kode($data->keterangan)</b>, PTKP per tahun <b>Rp. $data->ptkp_tahun</b>, PTKP per bulan <b>Rp. $data->ptkp_bulan</b>";
                LogActivity::create($activity);
            } else {
                Alert::error('Terjadi Kesalahan', 'Data PTKP Tidak Ditemukan.');
                return redirect()->route('ptkp.index');
            }

            Alert::success('Berhasil', 'Berhasil Hapus Penghasilan Tanpa Pajak.');
            return redirect()->route('ptkp.index');
        } catch (Exception $e) {
            Alert::error('Terjadi Kesalahan', '' . $e);
            return redirect()->route('ptkp.index')->withStatus($e->getMessage());
        } catch (QueryException $e) {
            Alert::error('Terjadi Kesalahan', '' . $e);
            return redirect()->route('ptkp.index')->withStatus($e->getMessage());
        }
    }

}
