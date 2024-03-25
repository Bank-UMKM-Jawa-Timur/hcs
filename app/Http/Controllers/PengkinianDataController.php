<?php

namespace App\Http\Controllers;

use App\Imports\PengkinianDataImport;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PengkinianKaryawanModel;
use App\Models\PengkinianPjsModel;
use App\Models\PjsModel;
use App\Models\SpModel;
use App\Repository\CabangRepository;
use App\Repository\PengkinianDataRepository;
use App\Service\EntityService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PengkinianDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        if (!auth()->user()->can('manajemen karyawan - pengkinian data - import pengkinian data')) {
            return view('roles.forbidden');
        }
        return view('pengkinian_data.import');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('manajemen karyawan - pengkinian data')) {
            return view('roles.forbidden');
        }

        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
         $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;
        $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;

        $pengkinianDataRepo = new PengkinianDataRepository();
        $data_pusat = $pengkinianDataRepo->getData($search, $limit, $page);

        return view('pengkinian_data.index', [
        // return view('pengkinian_data.index-old', [
            'data' => $data_pusat
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('manajemen karyawan - pengkinian data - create pengkinian data')) {
            return view('roles.forbidden');
        }
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
        if (!auth()->user()->can('manajemen karyawan - pengkinian data - update pengkinian data')) {
            return view('roles.forbidden');
        }
        DB::beginTransaction();
        try {
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
                'Pangkat Dan Golongan' => 'required',
                'status_karyawan' => 'required|not_in:-',
                'skangkat' => 'required|not_in:-',
                'tanggal_pengangkat' => 'required|not_in:-'
            ]);
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
            $bagian = null;
            if ($request->get('subdiv') != null) {
                $entitas = $request->get('subdiv');
                if($request->get('jabatan') == 'PSD'){
                    $bagian = null;
                } else{
                    $bagian =  $request->get('bagian');
                }
            } else if ($request->get('cabang') != null) {
                $entitas = $request->get('cabang');
                if($request->get('jabatan') == 'PC' || $request->get('jabatan') == 'PBO'){
                    $bagian = null;
                } else {
                    $bagian = $request->get('bagian');
                }
            } else {
                $entitas = $request->get('divisi');
                if($request->get('jabatan') == 'PIMDIV' || $request->get('jabatan') == 'PBO'){
                    $bagian = null;
                } else{
                    $bagian = $request->get('bagian');
                }
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
                    'kd_bagian' => $bagian,
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

            if ($request->is_jml_anak != null && intval($request->is_jml_anak) > 0) {
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
                                'enum' => 'Anak',
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

            if($request->idAnakDeleted != null) {
                $idAnakDeleted = explode(',', $request->idAnakDeleted);
                DB::table('keluarga')
                    ->whereIn('id', $idAnakDeleted)
                    ->delete();
            }

            DB::commit();
            Alert::success('Berhasil', 'Berhasil melakukan pengkinian data karyawan.');
            return redirect()->route('pengkinian_data.index');
        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('Tejadi kesalahan', '' . $e->getMessage());
            return redirect()->route('pengkinian_data.index');
        } catch (QueryException $e) {
            DB::rollBack();
            Alert::error('Tejadi kesalahan', '' . $e->getMessage());
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
        if (!auth()->user()->can('manajemen karyawan - pengkinian data - detail pengkinian data')) {
            return view('roles.forbidden');
        }
        $data_suis = null;

        $karyawan = PengkinianKaryawanModel::findOrFail($id);
        $data_suis = DB::table('history_pengkinian_data_keluarga')
            ->where('nip', $karyawan->nip)
            ->whereIn('enum', ['Suami', 'Istri'])
            ->first();
        $data_anak = DB::table('history_pengkinian_data_keluarga')
            ->where('nip', $karyawan->nip)
            ->whereIn('enum', ['Anak'])
            ->where('anak_ke', '<=', 2)
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

        $pjs = PjsModel::where('nip', $id)
            ->get();

        // Get Pergerakan Karir Detail
        $pergerakanKarir = DB::table('demosi_promosi_pangkat')
            ->where('demosi_promosi_pangkat.nip', $id)
            ->select(
                'demosi_promosi_pangkat.*',
                'karyawan.*',
                'newPos.nama_jabatan as jabatan_baru',
                'oldPos.nama_jabatan as jabatan_lama'
            )
            ->join('mst_karyawan as karyawan', 'karyawan.nip', '=', 'demosi_promosi_pangkat.nip')
            ->join('mst_jabatan as newPos', 'newPos.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_baru')
            ->join('mst_jabatan as oldPos', 'oldPos.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_lama')
            ->orderBy('demosi_promosi_pangkat.id', 'desc')
            ->get();
        $pergerakanKarir->map(function($data) {
            if(!$data->kd_entitas_baru) {
                $data->kantor_baru = "";
                return;
            }

            $entity = EntityService::getEntity($data->kd_entitas_baru);
            $type = $entity->type;

            if($type == 2) $data->kantor_baru = "Cab. " . $entity->cab->nama_cabang;

            if($type == 1) {
                $data->kantor_baru = isset($entity->subDiv) ?
                $entity->subDiv->nama_subdivisi . " (Pusat)":
                $entity->div->nama_divisi . " (Pusat)";
            }

            return $data;
        });
        $pergerakanKarir->map(function($dataLama) {
            if(!$dataLama->kd_entitas_lama) {
                $dataLama->kantor_lama = "";
                return;
            }

            $entityLama = EntityService::getEntity($dataLama->kd_entitas_lama);
            $typeLama = $entityLama->type;

            if($typeLama == 2) $dataLama->kantor_lama = "Cab. " . $entityLama->cab->nama_cabang;
            if($typeLama == 1) {
                $dataLama->kantor_lama = isset($entityLama->subDiv) ?
                $entityLama->subDiv->nama_subdivisi . " (Pusat)":
                $entityLama->div->nama_divisi." (Pusat)";
            }

            return $dataLama;
        });
        $historyJabatan = array();
        $dataHistory = array();
        foreach($pergerakanKarir as $item){
            array_push($dataHistory, [
                'tanggal_pengesahan' => $item?->tanggal_pengesahan,
                'lama' =>  $item?->kd_panggol_lama . ' ' . (($item->status_jabatan_lama != null) ? $item->status_jabatan_lama.' - ' : '') . ' ' . $item->jabatan_lama . ' ' . $item->kantor_lama ?? '-',
                'baru' => $item?->kd_panggol_baru . ' ' . (($item->status_jabatan_baru != null) ? $item->status_jabatan_baru.' - ' : '') . ' ' . $item->jabatan_baru . ' ' . $item->kantor_baru ?? '-',
                'bukti_sk' => $item?->bukti_sk,
                'keterangan' => $item?->keterangan
            ]);
        }
        foreach($pjs as $item){
            array_push($historyJabatan, [
                'mulai' => $item?->tanggal_mulai,
                'berakhir' => $item?->tanggal_berakhir,
                'jabatan' => jabatanLengkap($item),
                'no_sk' => $item?->no_sk,
                'keterangan' => null
            ]);
        }
        usort($dataHistory, fn($a, $b) => strtotime($a["tanggal_pengesahan"]) - strtotime($b["tanggal_pengesahan"]));
        foreach($dataHistory as $key => $item){
            array_push($historyJabatan, [
                'mulai' => $item['tanggal_pengesahan'],
                'berakhir' => ($key + 1 == count($dataHistory)) ? null : $dataHistory[$key + 1]['tanggal_pengesahan'],
                'jabatan' => $item['baru'],
                'status' => null,
                'no_sk' => $item['bukti_sk']
            ]);
        }
        usort($historyJabatan, fn($a, $b) => strtotime($a["mulai"]) - strtotime($b["mulai"]));

        // Get SP
        $sp = SpModel::where('nip', $id)->get();
        // dd($sp);

        return view('pengkinian_data.detail', [
            'karyawan' => $karyawan,
            'suis' => $data_suis,
            'tunjangan' => $data_tunjangan,
            'data_anak' => $data_anak,
            'pjs' => $historyJabatan,
            'sp' => $sp
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
            ->whereIn('enum', ['Anak'])
            ->where('anak_ke', '<=', 2)
            ->get();

        return response()->json([
            'data' => $data,
            'is' => $data_is,
            'data_anak' => $data_anak
        ]);
    }
}
