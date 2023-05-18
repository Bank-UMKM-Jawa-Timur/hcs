<?php

namespace App\Http\Controllers;

use App\Imports\PengkinianDataImport;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PengkinianKaryawanModel;
use App\Models\PengkinianPjsModel;
use RealRashid\SweetAlert\Facades\Alert;

class PengkinianDataController extends Controller
{
    public function postPengkinianImport(Request $request)
    {
        try{
            $file = $request->file('upload_csv');
            $import = new PengkinianDataImport;
            $import->import($file);

            Alert::success('Berhasil', 'Berhasil melakukan pengkinian data.');
            return redirect()->route('pengkinian_data.index');
        } catch(Exception $e){
            Alert::error('Gagal', 'Gagal melakukan pengkinian data. '. $e);
            return $e;
        } catch(QueryException $e){
            return 'Error query'. $e;
        }
    }

    public function pengkinian_data_index()
    {
        return view('pengkinian_data.import');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cbg = array();
        $cabang = DB::table('mst_cabang')
            ->get();
        foreach($cabang as $i){
            array_push($cbg, $i->kd_cabang);
        }
        $data_pusat = DB::select("SELECT history_pengkinian_data_karyawan.id, history_pengkinian_data_karyawan.nip, history_pengkinian_data_karyawan.nik, history_pengkinian_data_karyawan.nama_karyawan, history_pengkinian_data_karyawan.kd_entitas, history_pengkinian_data_karyawan.kd_jabatan, history_pengkinian_data_karyawan.kd_bagian, history_pengkinian_data_karyawan.ket_jabatan, history_pengkinian_data_karyawan.status_karyawan, mst_jabatan.nama_jabatan, history_pengkinian_data_karyawan.status_jabatan FROM `history_pengkinian_data_karyawan` JOIN mst_jabatan ON mst_jabatan.kd_jabatan = history_pengkinian_data_karyawan.kd_jabatan WHERE history_pengkinian_data_karyawan.kd_entitas NOT IN('".implode("', '", $cbg)."') or history_pengkinian_data_karyawan.kd_entitas IS NULL ORDER BY CASE WHEN history_pengkinian_data_karyawan.kd_jabatan='PIMDIV' THEN 1 WHEN history_pengkinian_data_karyawan.kd_jabatan='PSD' THEN 2 WHEN history_pengkinian_data_karyawan.kd_jabatan='PC' THEN 3 WHEN history_pengkinian_data_karyawan.kd_jabatan='PBO' THEN 4 WHEN history_pengkinian_data_karyawan.kd_jabatan='PBP' THEN 5 WHEN history_pengkinian_data_karyawan.kd_jabatan='PEN' THEN 6 WHEN history_pengkinian_data_karyawan.kd_jabatan='ST' THEN 7 WHEN history_pengkinian_data_karyawan.kd_jabatan='IKJP' THEN 8 WHEN history_pengkinian_data_karyawan.kd_jabatan='NST' THEN 9 END ASC");
        // dd($data_pusat);
        return view('pengkinian_data.index', [
            'data_pusat' => $data_pusat,
            'cabang' => $cabang
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        return view('pengkinian_data.add', [
            'panggol' => $data_panggol,
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
        $nip = $request->nip;
        $request->validate([
            'nip' => 'required',
            'nik' => 'required',
            'nama' => 'required',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama' => 'required|not_in:-',
            'jk' => 'required|not_in:-',
            'status_pernikahan' => 'required|not_in:-',
            'kewarganegaraan' => 'required|not_in:-',
            'alamat_ktp' => 'required',
            'panggol' => 'required|not_in:-',
            'status_jabatan' => 'required|not_in:-',
            'kpj' => 'required',
            'jkn' => 'required',
            'gj_pokok' => 'required',
            'status_karyawan' => 'required|not_in:-',
            'skangkat' => 'required|not_in:-',
            'tanggal_pengangkat' => 'required|not_in:-'
        ]);

        try {
            $id_is = $request->get('id_pasangan');
            if ($request->get('status_pernikahan') == 'Kawin' && $request->get('is') != null) {
                if ($request->get('id_pasangan') == null) {
                    DB::table('keluarga')
                        ->insert([
                            'enum' => $request->get('is'),
                            'nama' => $request->get('is_nama'),
                            'tgl_lahir' => $request->get('is_tgl_lahir'),
                            'alamat' => $request->get('is_alamat'),
                            'pekerjaan' => $request->get('is_pekerjaan'),
                            'jml_anak' => $request->get('is_jml_anak'),
                            'sk_tunjangan' => $request->get('sk_tunjangan_is'),
                            'nip' => $request->get('nip'),
                            'created_at' => now()
                        ]);
                } else {
                    $dataIs = DB::table('keluarga')
                        ->where('id', $id_is)
                        ->first();
                    DB::table('keluarga')
                        ->where('id', $id_is)
                        ->update([
                            'enum' => $request->get('is'),
                            'nama' => $request->get('is_nama'),
                            'tgl_lahir' => $request->get('is_tgl_lahir'),
                            'alamat' => $request->get('is_alamat'),
                            'pekerjaan' => $request->get('is_pekerjaan'),
                            'jml_anak' => $request->get('is_jml_anak'),
                            'sk_tunjangan' => $request->get('sk_tunjangan_is'),
                            'nip' => $request->get('nip'),
                            'updated_at' => now()
                        ]);
                    DB::table('history_pengkinian_data_keluarga')
                        ->insert([
                            'enum' => $dataIs->is,
                            'nama' => $dataIs->is_nama,
                            'tgl_lahir' => $dataIs->is_tgl_lahir,
                            'alamat' => $dataIs->is_alamat,
                            'pekerjaan' => $dataIs->is_pekerjaan,
                            'jml_anak' => $dataIs->is_jml_anak,
                            'sk_tunjangan' => $dataIs->sk_tunjangan_is,
                            'nip' => $dataIs->nip,
                            'created_at' => now()
                        ]);
                }
            }
            $entitas = null;
            if ($request->get('subdiv') != null) {
                $entitas = $request->get('subdiv');
            } else if ($request->get('cabang') != null) {
                $entitas = $request->get('cabang');
            } else {
                $entitas = $request->get('divisi');
            }

            $karyawan = DB::table('mst_karyawan')
                ->where('nip', $nip)
                ->first();

            DB::table('mst_karyawan')
                ->where('nip', $nip)
                ->update([
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
                    'gj_pokok' => str_replace('.', "", $request->get('gj_pokok')),
                    'gj_penyesuaian' => str_replace('.', "", $request->get('gj_penyesuaian')),
                    'status_karyawan' => $request->get('status_karyawan'),
                    'status_jabatan' => $request->get('status_jabatan'),
                    'skangkat' => $request->get('skangkat'),
                    'tanggal_pengangkat' => $request->get('tanggal_pengangkat'),
                    'no_rekening' => $request->get('no_rek'),
                    'created_at' => now(),
                ]);
                DB::table('history_pengkinian_data_karyawan')
                    ->insert([
                        'nip' => $request->get('nip'),
                        'nama_karyawan' => $karyawan->nama_karyawan,
                        'nik' => $karyawan->nik,
                        'ket_jabatan' => $karyawan->ket_jabatan,
                        'kd_entitas' => $karyawan->kd_entitas,
                        'kd_bagian' => $karyawan->kd_bagian,
                        'kd_jabatan' => $karyawan->kd_jabatan,
                        'kd_panggol' => $karyawan->kd_panggol,
                        'kd_agama' => $karyawan->kd_agama,
                        'tmp_lahir' => $karyawan->tmp_lahir,
                        'tgl_lahir' => $karyawan->tgl_lahir,
                        'kewarganegaraan' => $karyawan->kewarganegaraan,
                        'jk' => $karyawan->jk,
                        'status' => $karyawan->status,
                        'alamat_ktp' => $karyawan->alamat_ktp,
                        'alamat_sek' => $karyawan->alamat_sek,
                        'kpj' => $karyawan->kpj,
                        'jkn' => $karyawan->jkn,
                        'gj_pokok' => $karyawan->gj_pokok,
                        'gj_penyesuaian' => $karyawan->gj_penyesuaian,
                        'status_karyawan' => $karyawan->status_karyawan,
                        'status_jabatan' => $karyawan->status_jabatan,
                        'skangkat' => $karyawan->skangkat,
                        'tanggal_pengangkat' => $karyawan->tanggal_pengangkat,
                        'no_rekening' => $karyawan->no_rekening,
                        'created_at' => now(),
                    ]);

            if ($request->is_jml_anak != null) {
                foreach ($request->get('nama_anak') as $key => $item) {
                    if ($request->get('id_anak')[$key] != null) {
                        DB::table('keluarga')
                            ->where('id', $request->get('id_anak')[$key])
                            ->update([
                                'nama' => $item,
                                'tgl_lahir' => $request->get('tgl_lahir_anak')[$key],
                                'sk_tunjangan' => $request->get('sk_tunjangan_anak')[$key],
                                'nip' => $request->get('nip'),
                                'updated_at' => now()
                            ]);
                        DB::table('history_pengkinian_data_keluarga')
                            ->insert([
                                'nama' => $item,
                                'tgl_lahir' => $request->get('tgl_lahir_anak')[$key],
                                'sk_tunjangan' => $request->get('sk_tunjangan_anak')[$key],
                                'nip' => $request->get('nip'),
                                'created_at' => now()
                            ]);
                    } else {
                        DB::table('keluarga')
                            ->insert([
                                'enum' => ($key == 0) ? 'ANAK1' : 'ANAK2',
                                'nama' => $item,
                                'tgl_lahir' => $request->get('tgl_lahir_anak')[$key],
                                'sk_tunjangan' => $request->get('sk_tunjangan_anak')[$key],
                                'nip' => $request->get('nip'),
                                'created_at' => now()
                            ]);
                    }
                }
            }

            if($request->tunjangan[0] != null){
                for ($i = 0; $i < count($request->get('tunjangan')); $i++) {
                    if ($request->get('id_tk')[$i] == null) {
                        DB::table('tunjangan_karyawan')
                            ->insert([
                                'nip' => $request->get('nip'),
                                'id_tunjangan' => str_replace('.', '', $request->get('tunjangan')[$i]),
                                'nominal' =>  str_replace('.', '', $request->get('nominal_tunjangan')[$i]),
                                'created_at' => now()
                            ]);
                    } else {
                        DB::table('tunjangan_karyawan')
                            ->where('id', $request->get('id_tk')[$i])
                            ->update([
                                'nip' => $request->get('nip'),
                                'id_tunjangan' =>  str_replace('.', '', $request->get('tunjangan')[$i]),
                                'nominal' =>  str_replace('.', '', $request->get('nominal_tunjangan')[$i]),
                                'updated_at' => now()
                            ]);
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $request->get('nip'),
                                'id_tunjangan' =>  str_replace('.', '', $request->get('tunjangan')[$i]),
                                'nominal' =>  str_replace('.', '', $request->get('nominal_tunjangan')[$i]),
                                'updated_at' => now()
                            ]);
                    }
                }
            }

            Alert::success('Berhasil', 'Berhasil melakukan pengkinian data karyawan.');
            return redirect()->route('pengkinian_data.index');
        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('Tejadi kesalahan', '' . $e);
            return redirect()->route('pengkinian_data.index');
        } catch (QueryException $e) {
            DB::rollBack();
            Alert::error('Tejadi kesalahan', '' . $e);
            return redirect()->route('pengkinian_data.index');
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
        $data_suis = null;
        
        $karyawan = PengkinianKaryawanModel::findOrFail($id);
        $data_suis = DB::table('history_pengkinian_data_keluarga')
            ->where('nip', $karyawan->nip)
            ->whereIn('enum', ['Suami', 'Istri'])
            ->first();
        $data_anak = DB::table('history_pengkinian_data_keluarga')
            ->where('nip', $karyawan->nip)
            ->whereIn('enum', ['ANAK1', 'ANAK2'])
            ->get();
        $karyawan->tunjangan = DB::table('history_pengkinian_tunjangan_karyawan')
            ->where('nip', $id)
            ->select('history_pengkinian_tunjangan_karyawan.*')
            ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'history_pengkinian_tunjangan_karyawan.id')
            ->get();
        $karyawan->count_tj = DB::table('history_pengkinian_tunjangan_karyawan')
            ->where('nip', $id)
            ->count('*');
        $data_tunjangan = DB::table('mst_tunjangan')
            ->get();

        $pjs = PengkinianPjsModel::where('nip', $id)
            ->get();

        return view('pengkinian_data.detail', [
            'karyawan' => $karyawan,
            'suis' => $data_suis,
            'tunjangan' => $data_tunjangan,
            'data_anak' => $data_anak,
            'pjs' => $pjs,
        ]);
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

    public function getDataKaryawanByNIP(Request $request)
    {
        $nip = $request->get('nip');

        $data = DB::table('mst_karyawan')
            ->where('nip', $nip)
            ->first();
        $data->tunjangan = DB::table('tunjangan_karyawan')
            ->where('nip', $nip)
            ->select('tunjangan_karyawan.*')
            ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
            ->get();
        $data->count_tj = DB::table('tunjangan_karyawan')
            ->where('nip', $nip)
            ->count('*');
        $data_is = DB::table('keluarga')
            ->where('nip', $nip)
            ->whereIn('enum', ['Suami', 'Istri'])
            ->first();
        $data_anak = DB::table('keluarga')
            ->where('nip', $nip)
            ->whereIn('enum', ['ANAK1', 'ANAK2'])
            ->get();

        return response()->json([
            'data' => $data,
            'is' => $data_is,
            'data_anak' => $data_anak
        ]);
    }
}
