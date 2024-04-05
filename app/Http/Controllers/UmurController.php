<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class UmurController extends Controller
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
    public function index()
    {
        // Need permission
        $data = DB::table('mst_ru')
            ->get();

        return view('umur.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
        return view('umur.add');
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
            'umur_awal' => 'required',
            'umur_akhir' => 'required',
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try {
            DB::table('mst_ru')
                ->insert([
                    'u_awal' => $request->get('umur_awal'),
                    'u_akhir' => $request->get('umur_akhir'),
                    'created_at' => now()
                ]);

            // Record to log activity
            $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
            $activity = "Pengguna <b>$name</b> menambah rentang umur <b>$request->umur_awal - $request->umur_akhir</b>";
            LogActivity::create($activity);

            Alert::success('Berhasil', 'Berhasil Menambah Rentang Umur.');
            return redirect()->route('umur.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Rentang Umur.');
            return redirect()->route('umur.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Rentang Umur.');
            return redirect()->route('umur.index')->withStatus($e->getMessage());
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
        $data = DB::table('mst_ru')
            ->where('id', $id)
            ->first();

        return view('umur.edit', ['data' => $data]);
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
            'umur_awal' => 'required',
            'umur_akhir' => 'required',
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try {
            DB::table('mst_ru')
                ->where('id', $id)
                ->update([
                    'u_awal' => $request->get('umur_awal'),
                    'u_akhir' => $request->get('umur_akhir'),
                    'updated_at' => now()
                ]);

                // Record to log activity
                $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
                $activity = "Pengguna <b>$name</b> melakukan update rentang umur <b>$request->umur_awal - $request->umur_akhir</b>";
                LogActivity::create($activity);

            Alert::success('Berhasil', 'Berhasil Mengupdate Rentang Umur.');
            return redirect()->route('umur.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Mengupdate Rentang Umur.');
            return redirect()->route('umur.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Mengupdate Rentang Umur.');
            return redirect()->route('umur.index')->withStatus($e->getMessage());
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
        try{
            DB::table('mst_ru')
                ->where('id', $id)
                ->delete();

            Alert::success('Berhasil', 'Berhasil Menghapus Rentang Umur.');
            return redirect()->route('umur.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('umur.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('umur.index')->withStatus($e->getMessage());
        }
    }
}
