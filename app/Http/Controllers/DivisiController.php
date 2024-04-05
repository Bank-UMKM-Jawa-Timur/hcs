<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\DivisiModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DivisiController extends Controller
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
        $data = DivisiModel::select()->get();

        return view('divisi.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
        return view('divisi.add');
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
            'kode_divisi' => 'required',
            'nama_divisi' => 'required'
        ],[
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_divisi')
                ->insert([
                    'kd_divisi' => $request->get('kode_divisi'),
                    'nama_divisi' => $request->get('nama_divisi'),
                    'id_kantor' => 1,
                    'created_at' => now()
                ]);

                // Record to log activity
                $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
                $activity = "Pengguna <b>$name</b> menambah divisi dengan nama <b>$request->nama_divisi/b>";
                LogActivity::create($activity);
                Alert::success('Berhasil', 'Berhasil Menambah Divisi.');
                return redirect()->route('divisi.index');
            }catch(Exception $e){
                DB::rollBack();
                Alert::error('Terjadi Kesalahan', 'Kode Divisi Telah Digunakan.');
                return redirect()->route('divisi.index');
            }catch(QueryException $e){
                DB::rollBack();
                Alert::error('Terjadi Kesalahan', 'Gagal Menambahkan Divisi.');
                return redirect()->route('divisi.index');
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
        $data = DivisiModel::where('kd_divisi', $id)
            ->first();

        return view('divisi.edit', ['data' => $data]);
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
            'kode_divisi' => 'required',
            'nama_divisi' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_divisi')
                ->where('kd_divisi' ,$id)
                ->update([
                    'kd_divisi' => $request->get('kode_divisi'),
                    'nama_divisi' => $request->get('nama_divisi'),
                    'updated_at' => now()
                ]);

            // Record to log activity
            $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
            $activity = "Pengguna <b>$name</b> melakukan update divisi dengan nama <b>$request->nama_divisi/b>";
            LogActivity::create($activity);

            Alert::success('Berhasil', 'Berhasil Mengupdate Divisi.');
            return redirect()->route('divisi.index');
        }catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', 'Kode Divisi Telah Digunakan.');
            return redirect()->route('divisi.index');
        }catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', 'Gagal Mengupdate Divisi.');
            return redirect()->route('divisi.index');
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
            $divisiShow = DB::table('mst_divisi')
                ->where('id', $id)
                ->first()?->nama_divisi;
            DB::table('mst_divisi')
            ->where('id', $id)
            ->delete();

            // Record to log activity
            $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
            $activity = "Pengguna <b>$name</b> menghapus divisi dengan nama <b>$divisiShow/b>";
            LogActivity::create($activity);

            Alert::success('Berhasil', 'Berhasil Menghapus Divisi.');
            return redirect()->route('divisi.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('divisi.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('divisi.index');
        }
    }
}
