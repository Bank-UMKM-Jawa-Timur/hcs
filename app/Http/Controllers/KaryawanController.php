<?php

namespace App\Http\Controllers;

use App\Imports\ImportKaryawan;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class KaryawanController extends Controller
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
        $data = DB::table('mst_karyawan')
            ->select(
                'mst_karyawan.nip',
                'mst_karyawan.nik',
                'mst_karyawan.nama_karyawan',
                'mst_karyawan.kd_entitas',
                'mst_karyawan.kd_jabatan',
                'mst_karyawan.kd_bagian',
                'mst_karyawan.ket_jabatan',
                'mst_karyawan.status_karyawan',
                'mst_jabatan.nama_jabatan',
                'mst_karyawan.status_jabatan',
            )
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->get();
            // dd($data);

        return view('karyawan.index', ['data' => $data]);
    }

    public function import()
    {
        return view('karyawan.import');
    }

    public function upload_karyawan(Request $request)
    {
        Excel::import(new ImportKaryawan, $request->file('upload_csv'));
        
        Alert::success('Berhasil', 'Berhasil mengimport data excel');
        return redirect()->route('karyawan.index');
    }

    public function get_cabang()
    {
        $data = DB::table('mst_cabang')
            ->get();

        $data_bagian = DB::table('mst_bagian')
            ->where('kd_entitas', 2)
            ->get();

        return response()->json([$data, $data_bagian]);
    }

    public function get_divisi()
    {
        $data = DB::table('mst_divisi')
            ->select('kd_divisi', 'nama_divisi')
            ->get();

        return response()->json($data);
    }

    public function get_subdivisi(Request $request)
    {
        $data = DB::table('mst_sub_divisi')
            ->where('kd_divisi', $request->divisiID)
            ->get();

        return response()->json($data);
    }

    public function get_is(Request $request)
    {
        $data = DB::table('is')
            ->join('mst_karyawan', 'mst_karyawan.id_is', '=', 'is.id')
            ->select('is.*')
            ->where('nip', $request->nip)
            ->orderBy('id', 'desc')
            ->first();
        if(!isset($data)){
            $data = null;
        }

        return response()->json($data);
    }

    public function deleteEditTunjangan(Request $request)
    {
        $id = $request->id_tk;

        DB::table('tunjangan_karyawan')
            ->where('id', $id)
            ->delete();

            return response()->json("sukses");
    }

    public function get_bagian(Request $request)
    {
        $data = DB::table('mst_bagian')
            ->where('kd_entitas', $request->kd_entitas)
            ->get();
            
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data_is = DB::table('is')
            ->get();
        $data_panggol = DB::table('mst_pangkat_golongan')
            ->get();
        $data_jabatan = DB::table('mst_jabatan')
            ->get();
        $data_agama = DB::table('mst_agama')
            ->get();
        $data_tunjangan = DB::table('mst_tunjangan')
            ->get();
        $data_jabatan =  DB::table('mst_jabatan')
            ->get();

        return view('karyawan.add', [
            'panggol' => $data_panggol, 
            'is' => $data_is,
            'jabatan' => $data_jabatan,
            'agama' => $data_agama,
            'tunjangan' => $data_tunjangan,
            'jabatan' => $data_jabatan
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
        try{
            // dd($request);
            if($request->get('status_pernikahan') == 'Kawin'){
                DB::table('is')
                    ->insert([
                        'enum' => $request->get('is'),
                        'is_nama' => $request->get('is_nama'),
                        'is_tgl_lahir' => $request->get('is_tgl_lahir'),
                        'is_alamat' => $request->get('is_alamat'),
                        'is_pekerjaan' => $request->get('is_pekerjaan'),
                        'is_jml_anak' => $request->get('is_jml_anak'),
                        'created_at' => now()
                    ]);
            }
            $entitas = null;
            if($request->get('subdiv') != null){
                $entitas = $request->get('subdiv');
            } else if($request->get('cabang') != null){
                $entitas = $request->get('cabang');
            } else{
                $entitas = $request->get('divisi');
            }
            DB::table('mst_karyawan')
                ->insert([
                    'nip' => $request->get('nip'),
                    'nama_karyawan' => $request->get('nama'),
                    'nik' => $request->get('nik'),
                    'ket_jabatan' => $request->get('ket_jabatan'), 
                    'kd_entitas' => $entitas,
                    'kd_bagian' => $request->get('bagian'),
                    'kd_jabatan' => $request->get('jabatan'),
                    'kd_panggol' => $request->get('panggol'),
                    'kd_agama' => $request->get('agama'),
                    'tmp_lahir' => $request->get('tmp_lahir'),
                    'tgl_lahir' => $request->get('tgl_lahir'),
                    'kewarganegaraan' => $request->get('kewarganegaraan'),
                    'jk' => $request->get('jk'),
                    'status' => $request->get('status_pernikahan'),
                    'alamat_ktp' => $request->get('alamat_ktp'),
                    'alamat_sek' => $request->get('alamat_sek'),
                    'kpj' => $request->get('kpj'),
                    'jkn' => $request->get('jkn'),
                    'gj_pokok' => $request->get('gj_pokok'),
                    'gj_penyesuaian' => $request->get('gj_penyesuaian'),
                    'status_karyawan' => $request->get('status_karyawan'),
                    'status_jabatan' => $request->get('status_jabatan'),
                    'skangkat' => $request->get('skangkat'),
                    'tanggal_pengangkat' => $request->get('tanggal_pengangkat'),
                    'created_at' => now(),
                ]);

            if($request->get('status_pernikahan') == 'Kawin'){
                $id_is = DB::table('is')
                    ->select('id')
                    ->orderBy('id', 'DESC')
                    ->first();

                DB::table('mst_karyawan')
                    ->where('nip', $request->get('nip'))
                    ->update([
                        'id_is' => $id_is->id
                    ]);
            }

            for($i = 0; $i < count($request->get('tunjangan')); $i++){
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $request->get('nip'),
                        'id_tunjangan' => $request->get('tunjangan')[$i],
                        'nominal' => $request->get('nominal_tunjangan')[$i],
                        'created_at' => now()
                    ]);
            }

            Alert::success('Berhasil', 'Berhasil menambah karyawan.');
            return redirect()->route('karyawan.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Tejadi kesalahan', ''.$e);
            return redirect()->route('karyawan.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Tejadi kesalahan', ''.$e);
            return redirect()->route('karyawan.index');
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
        $data = DB::table('mst_karyawan')
            ->where('nip', $id)
            ->first();

        $data->tunjangan = DB::table('tunjangan_karyawan')
            ->where('nip', $id)
            ->select('tunjangan_karyawan.*')
            ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
            ->get();
        $data->count_tj = DB::table('tunjangan_karyawan')
            ->where('nip', $id)
            ->count('*');
        $data_is = DB::table('is')
            ->get();
        $data_panggol = DB::table('mst_pangkat_golongan')
            ->get();
        $data_jabatan = DB::table('mst_jabatan')
            ->get();
        $data_agama = DB::table('mst_agama')
            ->get();
        $data_tunjangan = DB::table('mst_tunjangan')
            ->get();

        return view('karyawan.edit', [
            'data' => $data,
            'panggol' => $data_panggol, 
            'is' => $data_is,
            'jabatan' => $data_jabatan,
            'agama' => $data_agama,
            'tunjangan' => $data_tunjangan
        ]);
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
        try{
            // dd($request);
            $id_is = $request->get('id_is');
            if($request->get('status_pernikahan') == 'Kawin'){
                $id_is = $request->get('id_is');
                if($request->get('id_is') == null){
                    DB::table('is')
                    ->insert([
                        'enum' => $request->get('is'),
                        'is_nama' => $request->get('is_nama'),
                        'is_tgl_lahir' => $request->get('is_tgl_lahir'),
                        'is_alamat' => $request->get('is_alamat'),
                        'is_pekerjaan' => $request->get('is_pekerjaan'),
                        'is_jml_anak' => $request->get('is_jml_anak'),
                        'created_at' => now()
                    ]);

                    $idis = DB::table('is')
                        ->select('id')
                        ->orderBy('id', 'desc')
                        ->first();
                        
                    DB::table('mst_karyawan')
                        ->where('nip', $request->get('nip'))
                        ->update([
                            'id_is' => $idis->id
                        ]);
                } else{
                    DB::table('is')
                        ->where('id', $id_is)
                        ->update([
                            'enum' => $request->get('is'),
                            'is_nama' => $request->get('is_nama'),
                            'is_tgl_lahir' => $request->get('is_tgl_lahir'),
                            'is_alamat' => $request->get('is_alamat'),
                            'is_pekerjaan' => $request->get('is_pekerjaan'),
                            'is_jml_anak' => $request->get('is_jml_anak'),
                            'updated_at' => now()
                        ]);
                }
            } else{
                DB::table('mst_karyawan')
                    ->where('nip', $request->get('nip'))
                    ->update([
                        'id_is' => null
                    ]);
                DB::table('is')
                    ->where('id', $request->get('id_is'))
                    ->delete();

            }

            DB::table('mst_karyawan')
                ->where('nip', $id)
                ->update([
                    'nip' => $request->get('nip'),
                    'nama_karyawan' => $request->get('nama'),
                    'nik' => $request->get('nik'),
                    'kd_agama' => $request->get('agama'),
                    'tmp_lahir' => $request->get('tmp_lahir'),
                    'tgl_lahir' => $request->get('tgl_lahir'),
                    'kewarganegaraan' => $request->get('kewarganegaraan'),
                    'jk' => $request->get('jk'),
                    'status' => $request->get('status_pernikahan'),
                    'alamat_ktp' => $request->get('alamat_ktp'),
                    'alamat_sek' => $request->get('alamat_sek'),
                    'kpj' => $request->get('kpj'),
                    'jkn' => $request->get('jkn'),
                    'gj_pokok' => $request->get('gj_pokok'),
                    'gj_penyesuaian' => $request->get('gj_penyesuaian'),
                    'status_karyawan' => $request->get('status_karyawan'),
                    'status_jabatan' => $request->get('status_jabatan'),
                    'skangkat' => $request->get('skangkat'),
                    'tanggal_pengangkat' => $request->get('tanggal_pengangkat'),
                    'created_at' => now(),
                ]);

                for($i = 0; $i < count($request->get('tunjangan')); $i++){
                    if($request->get('id_tk')[$i] == null){
                        DB::table('tunjangan_karyawan')
                            ->insert([
                                'nip' => $request->get('nip'),
                                'id_tunjangan' => $request->get('tunjangan')[$i],
                                'nominal' => $request->get('nominal_tunjangan')[$i],
                                'created_at' => now()
                            ]);
                    } else{
                        DB::table('tunjangan_karyawan')
                            ->where('id', $request->get('id_tk')[$i])
                            ->update([
                                'nip' => $request->get('nip'),
                                'id_tunjangan' => $request->get('tunjangan')[$i],
                                'nominal' => $request->get('nominal_tunjangan')[$i],
                                'updated_at' => now()
                            ]);
                    }
                }

                Alert::success('Berhasil', 'Berhasil mengupdate karyawan.');
                return redirect()->route('karyawan.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Tejadi kesalahan', ''.$e);
            return redirect()->route('karyawan.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Tejadi kesalahan', ''.$e);
            return redirect()->route('karyawan.index');
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
        //
    }
}
