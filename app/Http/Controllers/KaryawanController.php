<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Utils\PaginationController;
use App\Http\Requests\Karyawan\PenonaktifanRequest;
use App\Imports\ImportDataKeluarga;
use App\Imports\ImportKaryawan;
use App\Imports\ImportNpwpRekening;
use App\Imports\UpdateStatusImport;
use App\Imports\UpdateTunjanganImport;
use App\Models\JabatanModel;
use App\Models\KaryawanModel;
use App\Models\PanggolModel;
use App\Models\PjsModel;
use App\Models\SpModel;
use App\Models\UmurModel;
use App\Repository\KaryawanRepository;
use App\Service\EmployeeService;
use App\Service\EntityService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

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
    public function index(Request $request)
    {
        if (!Auth::user()->can('manajemen karyawan - data karyawan')) {
            return view('roles.forbidden');
        }
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $karyawanRepo = new KaryawanRepository();
        $search = $request->get('q');
        $data = $karyawanRepo->getAllKaryawan($search, $limit, $page);

        return view('karyawan.index', [
            'karyawan' => $data,
        ]);
    }

    public function listKaryawan()
    {
        return view('karyawan.list');
    }

    public function listKaryawanJson(Request $request) {
        $karyawanRepo = new KaryawanRepository();
        $data = $karyawanRepo->getAllKaryawan();
        $data = DataTables::collection($data)->toJson();
        // $data = DataTables::of($data)->make(true);
        return $data;
    }

    public function importStatusIndex()
    {
        return view('karyawan.import_update_status');
    }

    public function importStatus(Request $request)
    {
        $file = $request->file('upload_csv');
        $import = new UpdateStatusImport;
        $import = $import->import($file);

        Alert::success('Berhasil', 'Berhasil mengimport data excel');
        return redirect()->route('karyawan.index');
    }

    public function importNpwpRekeningIndex()
    {
        return view('karyawan.import_npwp_rekening');
    }

    public function importNpwpRekening(Request $request)
    {
        $file = $request->file('upload_csv');
        $import = new ImportNpwpRekening;
        $import = $import->import($file);

        Alert::success('Berhasil', 'Berhasil mengimport data excel');
        return redirect()->route('karyawan.index');
    }

    public function import()
    {
        if (!Auth::user()->can('manajemen karyawan - data karyawan - import karyawan')) {
            return view('roles.forbidden');
        }
        return view('karyawan.import');
    }

    public function upload_karyawan(Request $request)
    {
        $file = $request->file('upload_csv');
        $import = new ImportKaryawan;
        $import = $import->import($file);

        Alert::success('Berhasil', 'Berhasil mengimport data excel');
        return redirect()->route('karyawan.index');
        // dd($import->errors());
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
        $data['is'] = DB::table('keluarga')
            ->where('nip', $request->nip)
            ->whereIn('enum', ['Suami', 'Istri'])
            ->orderBy('id', 'desc')
            ->first();
        $data['anak'] = DB::table('keluarga')
            ->where('nip', $request->nip)
            ->whereIn('enum', ['ANAK1', 'ANAK2'])
            ->orderBy('id', 'desc')
            ->get();
        if (!isset($data)) {
            $data = null;
        }

        return response()->json($data);
    }

    public function deleteEditTunjangan(Request $request)
    {
        $id = $request->id_tk;
        $nip = DB::table('tunjangan_karyawan')
            ->where('id', $id)
            ->first('nip');

        DB::table('tunjangan_karyawan')
            ->where('id', $id)
            ->delete();

        DB::table('history_penyesuaian_gaji')
            ->insert([
                'keterangan' => 'Hapus Tunjangan',
                'nip' => $nip->nip,
                'nominal_lama' => 0,
                'nominal_baru' => 0,
                'created_at' => now()
            ]);

        return response()->json("sukses");
    }

    public function get_bagian(Request $request)
    {
        $data = DB::table('mst_bagian')
            ->where('kd_entitas', $request->kd_entitas)
            ->get();

        return response()->json($data);
    }

    public function getKantorKaryawan(Request $request)
    {
        $nip = $request->get('nip');
        $karyawan = DB::table('mst_karyawan')
            ->where('nip', $nip)
            ->first();
        $kantor = null;
        $kd_kantor = null;
        $div = null;
        $subdiv = null;
        $bag1 = null;

        if ($karyawan->kd_bagian != null || $karyawan->kd_bagian != '') {
            $bag1 = DB::table('mst_bagian')
                ->where('kd_bagian', $karyawan->kd_bagian)
                ->first();

            if ($bag1->kd_entitas != 2) {
                $kantor = 'Pusat';

                $subdiv = $bag1->kd_entitas;
                $subdivisi = DB::table('mst_sub_divisi')
                    ->where('kd_subdiv', $subdiv)
                    ->first();
                if (isset($subdivisi)) {
                    $div = DB::table('mst_divisi')
                        ->where('kd_divisi', $subdivisi->kd_divisi)
                        ->select('kd_divisi')
                        ->first();
                } else {
                    $div = DB::table('mst_divisi')
                        ->where('kd_divisi', $subdiv)
                        ->select('kd_divisi')
                        ->first();
                }
            } else {
                $kantor = 'Cabang';
                $kd_kantor = $karyawan->kd_entitas;

                $cabang = DB::table('mst_cabang')
                    ->where('kd_cabang', $karyawan->kd_entitas)
                    ->first();
            }
        } else {
            $cabang = DB::table('mst_cabang')->get();
            $cbg = array();
            foreach ($cabang as $i) {
                array_push($cbg, $i->kd_cabang);
            }
            if (in_array($karyawan->kd_entitas, $cbg)) {
                $kantor = 'Cabang';
                $kd_kantor = $karyawan->kd_entitas;
            } else {
                $kantor = 'Pusat';
                $subdiv = DB::table('mst_sub_divisi')
                    ->where('kd_subdiv', $karyawan->kd_entitas)
                    ->select('kd_subdiv')
                    ->first();
                $div = DB::table('mst_divisi')
                    ->where('kd_divisi', $karyawan->kd_entitas)
                    ->select('kd_divisi')
                    ->first();
            }
        }
        $data = [
            'kantor' => $kantor,
            'div' => $div,
            'subdiv' => $subdiv,
            'bag' => $bag1,
            'kd_kantor' => $kd_kantor
        ];

        return response()->json($data);
    }

    public function import_tunjangan()
    {
        return view('karyawan.update_tunjangan');
    }

    public function update_tunjangan(Request $request)
    {
        $file = $request->file('upload_csv');
        $import = new UpdateTunjanganImport;
        $import = $import->import($file);

        Alert::success('Berhasil', 'Berhasil mengimport data excel');
        return redirect()->route('karyawan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manajemen karyawan - data karyawan - create karyawan')) {
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

        return view('karyawan.add', [
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
            'kpj' => 'required',
            'jkn' => 'required',
            'gj_pokok' => 'required',
            'status_karyawan' => 'required|not_in:-',
            'jabatan' => 'required|not_in:-',
            'tgl_mulai' => 'required'
        ]);

        try {
            $entitas = null;
            if($request->kd_bagian && !isset($request->kd_cabang)){
                $entitas = null;
            }
            else if ($request->get('subdiv') != null) {
                $entitas = $request->get('subdiv');
            } else if ($request->get('cabang') != null) {
                $entitas = $request->get('cabang');
            } else {
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
                    'gj_pokok' => str_replace('.', "", $request->get('gj_pokok')),
                    'gj_penyesuaian' => str_replace('.', "", $request->get('gj_penyesuaian')),
                    'status_karyawan' => $request->get('status_karyawan'),
                    'status_jabatan' => $request->get('status_jabatan'),
                    'skangkat' => $request->get('skangkat'),
                    'tanggal_pengangkat' => $request->get('tanggal_pengangkat'),
                    'no_rekening' => $request->get('no_rek'),
                    'created_at' => now(),
                    'pendidikan' => $request->get('pendidikan'),
                    'pendidikan_major' => $request->get('pendidikan_major'),
                    'npwp' => $request->get('npwp'),
                    'tgl_mulai' => $request->get('tgl_mulai')
                ]);

            if ($request->get('status_pernikahan') == 'Kawin') {
                DB::table('keluarga')
                    ->insert([
                        'enum' => $request->get('is'),
                        'nama' => $request->get('is_nama'),
                        'tgl_lahir' => $request->get('is_tgl_lahir'),
                        'alamat' => $request->get('is_alamat'),
                        'pekerjaan' => $request->get('is_pekerjaan'),
                        'jml_anak' => $request->get('is_jml_anak'),
                        'nip' => $request->get('nip'),
                        'sk_tunjangan' => $request->get('sk_tunjangan_is'),
                        'created_at' => now()
                    ]);

                if ($request->get('nama_anak')[0] != null) {
                    foreach ($request->get('nama_anak') as $key => $item) {
                        DB::table('keluarga')
                            ->insert([
                                'enum' => ($key == 0) ? 'ANAK1' : 'ANAK2',
                                'nama' => $item,
                                'tgl_lahir' => $request->get('tgl_lahir_anak')[$key],
                                'nip' => $request->get('nip'),
                                'sk_tunjangan' => $request->get('sk_tunjangan_anak')[$key]
                            ]);
                    }
                }
            }

            for ($i = 0; $i < count($request->get('tunjangan')); $i++) {
                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $request->get('nip'),
                        'id_tunjangan' =>  str_replace('.', '', $request->get('tunjangan')[$i]),
                        'nominal' =>  str_replace('.', '', $request->get('nominal_tunjangan')[$i]),
                        'created_at' => now()
                    ]);
            }

            Alert::success('Berhasil', 'Berhasil menambah karyawan.');
            return redirect()->route('karyawan.index');
        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('Tejadi kesalahan', '' . $e);
            return redirect()->route('karyawan.index');
        } catch (QueryException $e) {
            DB::rollBack();
            Alert::error('Tejadi kesalahan', '' . $e);
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
        if (!Auth::user()->can('manajemen karyawan - data karyawan - detail karyawan')) {
            return view('roles.forbidden');
        }
        $data_suis = null;
        $karyawan = KaryawanModel::findOrFail($id);
        $data_suis = DB::table('keluarga')
            ->where('nip', $karyawan->nip)
            ->orderByDesc('id')
            ->first();
        $data_anak = DB::table('keluarga')
            ->where('nip', $karyawan->nip)
            ->whereIn('enum', ['ANAK1', 'ANAK2'])
            ->get();
        $karyawan->tunjangan = DB::table('tunjangan_karyawan')
            ->where('nip', $id)
            ->select('tunjangan_karyawan.*')
            ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id')
            ->get();
        $karyawan->count_tj = DB::table('tunjangan_karyawan')
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
            ->orderBy('id', 'desc')
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

        $dppPerhitungan = DB::table('gaji_per_bulan')
                ->select(
                    'gj_pokok',
                    'tj_keluarga',
                    'tj_kesejahteraan',
                )
                ->where('nip', $id)
                ->first();
        
        $totalDppPerhitungan = 0;
        if ($dppPerhitungan) {
            $gjPokok = $dppPerhitungan->gj_pokok;
            $tjKeluarga = $dppPerhitungan->tj_keluarga;
            $tjKesejahteraan = $dppPerhitungan->tj_kesejahteraan;
            $totalDppPerhitungan = ($gjPokok + $tjKeluarga + 0.5 * $tjKesejahteraan) * 0.05;
        }

        return view('karyawan.detail', [
            'karyawan' => $karyawan,
            'suis' => $data_suis,
            'tunjangan' => $data_tunjangan,
            'data_anak' => $data_anak,
            'pjs' => $historyJabatan,
            'sp' => $sp,
            'dpp_perhitungan' => $totalDppPerhitungan,
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
        if (!Auth::user()->can('manajemen karyawan - data karyawan - edit karyawan')) {
            return view('roles.forbidden');
        }
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
        $data_is = DB::table('keluarga')
            ->where('nip', $id)
            ->whereIn('enum', ['Suami', 'Istri'])
            ->first();
        $data_anak = DB::table('keluarga')
            ->where('nip', $id)
            ->whereIn('enum', ['ANAK1', 'ANAK2'])
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
            'tunjangan' => $data_tunjangan,
            'data_anak' => $data_anak
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
        // dd($request);
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
            'kpj' => 'required',
            'jkn' => 'required',
            'gj_pokok' => 'required',
            'status_karyawan' => 'required|not_in:-',
            'tgl_mulai' => 'required'
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
                }
            }
            $entitas = null;
            if($request->kd_bagian && !isset($request->kd_cabang)){
                $entitas = null;
            }
            else if ($request->get('subdiv') != null) {
                $entitas = $request->get('subdiv');
            } else if ($request->get('cabang') != null) {
                $entitas = $request->get('cabang');
            } else {
                $entitas = $request->get('divisi');
            }

            $karyawan = DB::table('mst_karyawan')
                ->where('nip', $id)
                ->first();

            DB::table('mst_karyawan')
                ->where('nip', $id)
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
                    'npwp' => $request->get('npwp'),
                    'tgl_mulai' => $request->get('tgl_mulai'),
                    'created_at' => now(),
                ]);

            if($request->get('status_pernikahan') == 'Kawin' && $request->get('is_jml_anak') > 0){
                if ($request->get('nama_anak')[0] != null) {
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
            }

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
                }
            }

            Alert::success('Berhasil', 'Berhasil mengupdate karyawan.');
            return redirect()->route('karyawan.index');
        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('Tejadi kesalahan', '' . $e);
            dd($e);
        } catch (QueryException $e) {
            DB::rollBack();
            Alert::error('Tejadi kesalahan', '' . $e);
            dd($e);
        }
    }

    public function penonaktifan(PenonaktifanRequest $request)
    {
        if($request->get('tanggal_penonaktifan') > now()) {
            Alert::error('Tanggal penonaktifan tidak boleh lebih dari hari ini.');
            return redirect()->route('penonaktifan.create');
        }
        EmployeeService::deactivate($request->only([
            'nip',
            'tanggal_penonaktifan',
            'kategori_penonaktifan',
        ]), $request->file('sk_pemberhentian'));

        Alert::success('Berhasil menonaktifkan karyawan');
        return redirect()->route('penonaktifan.index');
    }

    public function penonaktifanAdd()
    {
        if (!Auth::user()->can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan')) {
            return view('roles.forbidden');
        }
        return view('karyawan.penonaktifan.penonaktifan');
    }

    public function indexPenonaktifan()
    {
        if (!Auth::user()->can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan')) {
            return view('roles.forbidden');
        }
        $karyawanRepo = new KaryawanRepository();

        return view('karyawan.penonaktifan.index', [
            'karyawan' => $karyawanRepo->getAllKaryawanNonaktif()
        ]);
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

    public function importKeluargaIndex()
    {
        return view('karyawan.import_data_keluarga');
    }

    public function importKeluarga(Request $request)
    {
        $file = $request->file('upload_csv');
        $import = new ImportDataKeluarga;
        $import = $import->import($file);

        Alert::success('Berhasil', 'Berhasil mengimport data keluarga');
        return redirect()->route('karyawan.index');
    }

    public function reminderPensiunIndex()
    {
        if (!Auth::user()->can('manajemen karyawan - data masa pensiunan')) {
            return view('roles.forbidden');
        }
        $jabatan = JabatanModel::all();
        $panggol = PanggolModel::all();

        return view('karyawan.reminder-pensiun', [
            'karyawan' => null,
            'status' => null,
            'jabatan' => $jabatan,
            'panggol' => $panggol,
        ]);
    }

    public function reminderPensiunShow(Request $request)
    {
        $kantor = $request->kantor;
        $karyawan = collect();
        $status = 0;

        $jabatan = JabatanModel::all();
        $panggol = PanggolModel::all();
        $umur = UmurModel::all();

        if ($request->kategori == 1) {
            $karyawan = KaryawanModel::query();

            $status = 1;
        }

        if ($request->kategori == 2) {
            $subDivs = DB::table('mst_sub_divisi')->where('kd_divisi', $request->divisi)
                ->pluck('kd_subdiv');

            $bagians = DB::table('mst_bagian')->whereIn('kd_entitas', $subDivs)
                ->orWhere('kd_entitas', $request->divisi)
                ->pluck('kd_bagian');

            $karyawan = KaryawanModel::where('kd_entitas', $request->divisi)
                ->orWhereIn('kd_entitas', $subDivs)
                ->orWhereIn('kd_bagian', $bagians);

            $status = 2;
        }

        if ($request->kategori == 3) {
            $entitas = $request->subDivisi ?? $request->divisi;

            $bagian = DB::table('mst_bagian')->where('kd_entitas', $entitas)
                ->pluck('kd_bagian');

            $karyawan = KaryawanModel::where('kd_entitas', $entitas)
                ->orWhereIn('kd_bagian', $bagian);

            $status = 3;
        }

        if ($request->kategori == 4) {
            $karyawan = KaryawanModel::where('kd_bagian', $request->bagian)->whereNotNull('kd_bagian');
            $status = 4;
        }

        if ($request->kategori == 5) {
            if ($kantor == 'Cabang') $karyawan = KaryawanModel::where('kd_entitas', $request->cabang);

            if ($kantor == 'Pusat') {
                $cbgs = DB::table('mst_cabang')->pluck('kd_cabang');
                $karyawan = KaryawanModel::whereNotIn('kd_entitas', $cbgs)
                    ->orWhere('kd_entitas', null);
            }

            $status = 5;
        }

        if ($karyawan instanceof Builder) {
            $karyawan->with('keluarga');
            $karyawan->whereNull('tanggal_penonaktifan');
            $karyawan->leftJoin('mst_jabatan', 'mst_jabatan.kd_jabatan', 'mst_karyawan.kd_jabatan');
            $karyawan->orderBy('tgl_lahir', 'asc');
            $karyawan = $karyawan->get();
        }
        // $date = date('Y-m-d', strtotime($karyawan[4]->tgl_lahir. ' + 56 years'));
        // dd($date);
        // dd($karyawan);
        return view('karyawan.reminder-pensiun', [
            'status' => $status,
            'karyawan' => $karyawan,
            'jabatan' => $jabatan,
            'panggol' => $panggol,
            'request' => $request,
            'umur' => $umur,
        ]);
    }

    public function exportCV($id)
    {
        $data_suis = null;
        $karyawan = KaryawanModel::findOrFail($id);
        $data_suis = DB::table('keluarga')
            ->where('nip', $karyawan->nip)
            ->whereIn('enum', ['Suami', 'Istri'])
            ->orderByDesc('id')
            ->first();
        $data_anak = DB::table('keluarga')
            ->where('nip', $karyawan->nip)
            ->whereIn('enum', ['ANAK1', 'ANAK2'])
            ->get();
        $karyawan->tunjangan = DB::table('tunjangan_karyawan')
            ->where('nip', $id)
            ->select('tunjangan_karyawan.*')
            ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id')
            ->get();
        $karyawan->count_tj = DB::table('tunjangan_karyawan')
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
            ->orderBy('id', 'desc')
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
        return view('karyawan.cv', [
            'karyawan' => $karyawan,
            'suis' => $data_suis,
            'tunjangan' => $data_tunjangan,
            'data_anak' => $data_anak,
            'pjs' => $historyJabatan,
            'sp' => $sp
        ]);
    }

    public function countAge($date_of_birth)
    {
        // PHP program to calculate age in years
        // Define the date of birth
        $dateOfBirth = '14-02-2022';

        // Get today's date
        $now = date("Y-m-d");

        // Calculate the time difference between the two dates
        $diff = date_diff(date_create($dateOfBirth), date_create($now));
        $year = $diff->format('%y');
        $month = $diff->format('%m');
        if ($year > 0) {
            $month += ($year * 12);
        }

        return $month;
    }
}
