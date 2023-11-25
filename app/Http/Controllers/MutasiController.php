<?php

namespace App\Http\Controllers;

use App\Http\Requests\MutasiRequest;
use App\Service\EntityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MutasiController extends Controller
{
    public function getDataKaryawan(Request $request)
    {
        $cabang = DB::table('mst_cabang')
            ->get();
        $cbg = array();
        foreach($cabang as $i){
            array_push($cbg, $i->kd_cabang);
        }
        $officer = DB::table('mst_karyawan')
            ->where('nip', $request->nip)
            // ->join('mst_pangkat_golongan', 'mst_pangkat_golongan.golongan', '=', 'mst_karyawan.kd_panggol')
            ->first();

        if(!$officer) return response()->json([
            'success' => false,
            'message' => 'Data karyawan tidak ditemukan',
        ]);

        if(isset($officer->kd_bagian)){
            if($officer->kd_entitas != null && !in_array($officer->kd_entitas, $cbg)){
                $entity = DB::table('mst_bagian')
                    ->where('kd_bagian', $officer->kd_bagian)
                    ->select('kd_entitas')
                    ->first();
                $officer->entitas = EntityService::getEntity($entity->kd_entitas);
            } else{
                $officer->entitas = EntityService::getEntity($officer->kd_entitas);
            }
        } else if($officer->kd_entitas == null){
            $officer->entitas = EntityService::getEntity($officer->kd_entitas);
        } else{
            $officer->entitas = EntityService::getEntity($officer->kd_entitas);
        }

        $officer->jabatan = DB::table('mst_jabatan')->where('kd_jabatan', $officer->kd_jabatan)->first();
        $officer->panggol = ($officer->kd_panggol != null) ? DB::table('mst_pangkat_golongan')->where('golongan', $officer->kd_panggol)->first() : null;
        $officer->bagian = ($officer->kd_bagian != null) ? DB::table('mst_bagian')->where('kd_bagian', $officer->kd_bagian)->first() : null;

        return response()->json([
            'success' => true,
            'karyawan' => $officer,
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->get('q');
        $data = DB::table('demosi_promosi_pangkat')
            ->where('keterangan', 'Mutasi')
            ->select(
                'demosi_promosi_pangkat.*',
                'karyawan.*',
                'newPos.nama_jabatan as jabatan_baru',
                'oldPos.nama_jabatan as jabatan_lama'
            )
            ->join('mst_karyawan as karyawan', 'karyawan.nip', '=', 'demosi_promosi_pangkat.nip')
            ->join('mst_jabatan as newPos', 'newPos.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_baru')
            ->join('mst_jabatan as oldPos', 'oldPos.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_lama')
            ->orderBy('tanggal_pengesahan', 'asc')
            ->when($search, function ($query) use ($search) {
                $query->where('karyawan.nama_karyawan', 'like', "%$search%")
                    ->orWhere('demosi_promosi_pangkat.nip', 'like', "%$search%")
                    ->orWhere('demosi_promosi_pangkat.kd_jabatan_lama', 'like', "%$search%")
                    ->orWhere('demosi_promosi_pangkat.kd_jabatan_baru', 'like', "%$search%")
                    ->orWhere('demosi_promosi_pangkat.status_jabatan_lama', 'like', "%$search%")
                    ->orWhere('demosi_promosi_pangkat.status_jabatan_baru', 'like', "%$search%");
            })
            // ->get();
            ->paginate($limit);

        $data->map(function($mutasi) {
            $entity = EntityService::getEntity($mutasi->kd_entitas_baru);
            $type = $entity->type;
            $mutasi->kantor_baru = '-';

            if($type == 2) $mutasi->kantor_baru = "Cab. " . $entity->cab->nama_cabang;
            if($type == 1) {
                if(isset($entity->subDiv)){
                    $mutasi->kantor_baru = $entity?->subDiv?->nama_subdivisi . " (Pusat)";
                } else if(isset($entity->div)){
                    $mutasi->kantor_baru = $entity?->div?->nama_divisi . " (Pusat)";
                }
            }

            return $mutasi;
        });

        $data->map(function($mutasiLama) {
            $entityLama = EntityService::getEntity($mutasiLama->kd_entitas_lama);
            $typeLama = $entityLama->type;
            $mutasiLama->kantor_lama = '-';

            if($typeLama == 2) $mutasiLama->kantor_lama = "Cab. " . $entityLama->cab->nama_cabang;
            if($typeLama == 1) {
                if(isset($entityLama->subDiv)){
                    $mutasiLama->kantor_lama = $entityLama->subDiv->nama_subdivisi . " (Pusat)";
                } else if(isset($entityLama->div)){
                    $mutasiLama->kantor_lama = $entityLama->div->nama_divisi . " (Pusat)";
                }
            }

            return $mutasiLama;
        });


        return view('mutasi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = DB::table('mst_karyawan')
            ->select('nip', 'nama_karyawan', 'kd_jabatan')
            ->get();
        $data_jabatan = DB::table('mst_jabatan')
            ->get();
        $tj = DB::table('mst_tunjangan')
            ->get();
        $panggol = DB::table('mst_pangkat_golongan')
            ->get();

        return view('mutasi.add', ['data' => $data, 'jabatan' => $data_jabatan, 'tunjangan' => $tj, 'panggol' => $panggol]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MutasiRequest $request)
    {
        if($request->tunjangan != null){
            if(count($request->tunjangan) > 0){
                foreach($request->tunjangan as $key => $item){
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
        $promosi = DB::table('demosi_promosi_pangkat')
            ->insert([
                'nip' => $request->nip,
                'tanggal_pengesahan' => $request->tanggal_pengesahan,
                'bukti_sk' => $request->bukti_sk,
                'keterangan' => 'Mutasi',
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

        if(!$promosi) {
            Alert::error('Error', 'Gagal menambahkan data mutasi.');
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

        Alert::success('Berhasil', 'Berhasil menambahkan data mutasi.');
        return redirect()->route('mutasi.index');
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
