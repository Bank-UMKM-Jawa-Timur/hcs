<?php

namespace App\Http\Controllers;

use App\Http\Requests\Karyawan\PenonaktifanRequest;
use App\Imports\ImportKaryawan;
use App\Imports\ImportNpwpRekening;
use App\Imports\UpdateStatusImport;
use App\Imports\UpdateTunjanganImport;
use App\Models\KaryawanModel;
use App\Repository\KaryawanRepository;
use App\Service\EmployeeService;
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
        $karyawanRepo = new KaryawanRepository();

        return view('karyawan.index', [
            'karyawan' => $karyawanRepo->getAllKaryawan()
        ]);
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
        $data = DB::table('is')
            ->join('mst_karyawan', 'mst_karyawan.id_is', '=', 'is.id')
            ->select('is.*')
            ->where('nip', $request->nip)
            ->orderBy('id', 'desc')
            ->first();
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

        if ($karyawan->kd_bagian != null || $karyawan->kd_bagian == '') {
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
                    ->select('kd_div')
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
            $entitas = null;
            if ($request->get('subdiv') != null) {
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

                if($request->get('nama_anak')[0] != null){
                    foreach($request->get('nama_anak') as $key => $item){
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
        $data_suis = null;
        $karyawan = KaryawanModel::findOrFail($id);
        $data_suis = DB::table('keluarga')
            ->where('nip', $karyawan->nip)
            ->whereIn('enum', ['Suami', 'Istri'])
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

        return view('karyawan.detail', [
            'karyawan' => $karyawan,
            'suis' => $data_suis,
            'tunjangan' => $data_tunjangan,
            'data_anak' => $data_anak
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
                if($request->get('id_pasangan') == null){
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
                } else{
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
            if ($request->get('subdiv') != null) {
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
                    'created_at' => now(),
                ]);
            
            if($request->get('nama_anak')[0] != null){
                foreach($request->get('nama_anak') as $key => $item){
                    if($request->get('id_anak')[$key] != null){
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
            return $e->getMessage();
        } catch (QueryException $e) {
            DB::rollBack();
            Alert::error('Tejadi kesalahan', '' . $e);
            return $e->getMessage();
        }
    }

    public function penonaktifan(PenonaktifanRequest $request)
    {
        if ($request->isMethod('GET')) return view('karyawan.penonaktifan');

        EmployeeService::deactivate($request->only([
            'nip',
            'tanggal_penonaktifan',
            'kategori_penonaktifan',
        ]), $request->file('sk_pemberhentian'));

        Alert::success('Berhasil menonaktifkan karyawan');
        return back();
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
