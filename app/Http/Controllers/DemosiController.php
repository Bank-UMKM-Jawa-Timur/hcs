<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Http\Requests\DemosiRequest;
use App\Repository\DemosiRepository;
use App\Service\EntityService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DemosiController extends Controller
{
    public function getgolongan(Request $request)
    {
        $data = DB::table('mst_karyawan')
            ->select('nip', 'nama_karyawan', 'mst_pangkat_golongan.golongan', 'mst_pangkat_golongan.pangkat')
            ->where('nip', $request->get('nip'))
            ->join('mst_pangkat_golongan', 'mst_pangkat_golongan.golongan', '=', 'mst_karyawan.kd_panggol')
            ->first();

        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Need permission
        if (!auth()->user()->can('manajemen karyawan - pergerakan karir - data demosi')) {
            return view('roles.forbidden');
        }

        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;

        $repo = new DemosiRepository;
        $data = $repo->get($search, $limit);

        return view('demosi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('manajemen karyawan - pergerakan karir - data demosi - create demosi')) {
            return view('roles.forbidden');
        }
        $data = DB::table('mst_karyawan')
            ->select('nip', 'nama_karyawan', 'kd_panggol')
            ->get();
        $data_panggol = DB::table('mst_jabatan')
            ->get();
        $tj = DB::table('mst_tunjangan')
            ->get();
        $panggol = DB::table('mst_pangkat_golongan')
            ->get();

        return view('demosi.add', ['data' => $data, 'jabatan' => $data_panggol, 'tunjangan' => $tj, 'panggol' => $panggol]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DemosiRequest $request)
    {
        if (!auth()->user()->can('manajemen karyawan - pergerakan karir - data demosi - create demosi')) {
            return view('roles.forbidden');
        }
        DB::beginTransaction();
        try {
            if($request->tunjangan != null){
                if(count($request->tunjangan) > 0){
                    foreach($request->tunjangan as $key => $item){
                        if ($item) {
                            $tj = DB::table('mst_tunjangan')
                                ->where('id', $request->tunjangan[$key])
                                ->first('nama_tunjangan');

                            if($request->id_tk[$key] != 0){
                                DB::table('history_penyesuaian')
                                    ->insert([
                                        'nip' => $request->nip,
                                        'keterangan' => "Penyesuaian Tunjangan ".$tj->nama_tunjangan,
                                        'nominal_lama' => $request->nominal_lama[$key],
                                        'nominal_baru' => str_replace('.', '',$request->nominal_tunjangan[$key]),
                                        'id_tunjangan' => $item,
                                        'created_at' => now()
                                    ]);
                                DB::table('tunjangan_karyawan')
                                    ->where('id', $request->id_tk[$key])
                                    ->update([
                                        'id_tunjangan' => $item,
                                        'nominal' => str_replace('.', '',$request->nominal_tunjangan[$key]),
                                        'updated_at' => now()
                                    ]);
                            } else{
                                DB::table('tunjangan_karyawan')
                                    ->insert([
                                        'nip' => $request->nip,
                                        'id_Tunjangan' => ($request->tunjangan[$key] != null) ? $request->tunjangan[$key] : null,
                                        'nominal' => ($request->nominal_tunjangan[$key] != null) ? str_replace('.', '',$request->nominal_tunjangan[$key]) : null,
                                        'created_at' => now()
                                    ]);
                                DB::table('history_penyesuaian')
                                    ->insert([
                                        'nip' => $request->nip,
                                        'keterangan' => "Penambahan Tunjangan ".$tj->nama_tunjangan,
                                        'nominal_lama' => $request->nominal_lama[$key],
                                        'nominal_baru' =>  ($request->nominal_tunjangan[$key] != null) ? str_replace('.', '',$request->nominal_tunjangan[$key]) : null,
                                        'id_tunjangan' => $item,
                                        'created_at' => now()
                                    ]);
                            }
                        }
                    }
                }
            }

            $filename = null;
            if($request->file_sk != null){
                $file = $request->file_sk;
                $folderPath = public_path() . '/upload/pergerakan_karir/';
                $filename = date('YmdHis').'.'. $file->getClientOriginalExtension();
                $path = realpath($folderPath);

                if(!($path !== true AND is_dir($path))){
                    mkdir($folderPath, 0755, true);
                }
                $file->move($folderPath, $filename);
            }
            $karyawan = DB::table('mst_karyawan')
                ->select('gj_pokok', 'gj_penyesuaian')
                ->where('nip', $request->nip)
                ->first();
            DB::table('history_penyesuaian')
                ->insert([
                    'nip' => $request->nip,
                    'keterangan' => 'Penyesuaian Gaji Pokok',
                    'nominal_baru' => str_replace('.', '', $request->gj_pokok),
                    'nominal_lama' => $karyawan->gj_pokok,
                    'created_At' => now()
                ]);

            DB::table('history_penyesuaian')
                ->insert([
                    'nip' => $request->nip,
                    'keterangan' => 'Penyesuaian Gaji Penyesuaian',
                    'nominal_baru' => str_replace('.', '', $request->gj_penyesuaian),
                    'nominal_lama' => $karyawan->gj_penyesuaian,
                    'created_At' => now()
                ]);

            DB::table('mst_karyawan')
                ->where('nip', $request->nip)
                ->update([
                    'gj_pokok' => str_replace('.', '', $request->gj_pokok),
                    'gj_penyesuaian' => str_replace('.', '', $request->gj_penyesuaian)
                ]);
            $entity = EntityService::getEntityFromRequest($request);
            $entityLama = $request->kd_entity;
            if($request->kd_bagian_lama != null)
                $entityLama = EntityService::getFromBranch($request->kd_bagian_lama);
            $demosi = DB::table('demosi_promosi_pangkat')
                ->insert([
                    'nip' => $request->nip,
                    'tanggal_pengesahan' => $request->tanggal_pengesahan,
                    'bukti_sk' => $request->bukti_sk,
                    'keterangan' => 'Demosi',
                    'kd_entitas_lama' => $entityLama,
                    'kd_entitas_baru' => $entity,
                    'kd_jabatan_lama' => $request->id_jabatan_lama,
                    'kd_jabatan_baru' => $request->id_jabatan_baru,
                    'kd_bagian' => $request->kd_bagian,
                    'kd_bagian_lama' => $request->bagian_lama,
                    'kd_panggol_lama' => $request->panggol_lama,
                    'kd_panggol_baru' => $request->panggol,
                    'status_jabatan_lama' => $request->status_jabatan_lama,
                    'status_jabatan_baru' => $request->status_jabatan,
                    'nip_lama' => $request->nip,
                    'nip_baru' => $request->nip_baru,
                    'file_sk' => $filename,
                    'created_at' => now(),
                ]);

            if(!$demosi) {
                Alert::error('Error', 'Gagal menambahkan data demosi.');
                return back()->withInput();
            }

            $officer = DB::table('mst_karyawan')
                ->where('nip', $request->nip)
                ->update([
                    'nip' => $request->nip_baru ?? $request->nip,
                    'kd_jabatan' => $request->id_jabatan_baru,
                    'ket_jabatan' => $request->ket_jabatan,
                    'kd_entitas' => $entity,
                    'kd_bagian' => $request->kd_bagian,
                    'status_jabatan' => $request->status_jabatan,
                    'kd_panggol' => $request->panggol,
                    'updated_at' => now(),
                ]);

            if($officer < 1) {
                Alert::error('Error', 'Gagal mengupdate data karyawan');
                return back()->withInput();
            }

            // Record to log activity
            $namaKaryawan = DB::table('mst_karyawan')->select('nama_karyawan')->where('nip', $request->nip)->first()->nama_karyawan;
            $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
            $activity = "Pengguna <b>$name</b> melakukan demosi karyawan atas nama <b>$namaKaryawan</b>.";
            LogActivity::create($activity);

            DB::commit();
            Alert::success('Berhasil', 'Berhasil menambahkan data demosi');
            return redirect()->route('demosi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
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
}
