<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use App\Service\EntityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PromosiController extends Controller
{
    public function getgolongan(Request $request)
    {
        $data = DB::table('mst_karyawan')
            ->where('nip', $request->get('nip'))
            ->join('mst_pangkat_golongan', 'mst_pangkat_golongan.golongan', '=', 'mst_karyawan.kd_panggol')
            ->first();

        return response()->json($data);
    }

    public function getdatapromosi(Request $request)
    {
        $data = DB::table('mst_karyawan')
            ->where('nip', $request->nip)
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->first();

        return response()->json($data);
    }

    public function getDataGajiPromosi(Request $request)
    {
        $nip = $request->get('nip');

        $data_gj = DB::table('mst_karyawan')
            ->where('nip', $nip)
            ->select('gj_pokok', 'gj_penyesuaian')
            ->first();
        $data_tj = DB::table('tunjangan_karyawan')
            ->where('nip', $nip)
            ->get();
        $tj = DB::table('mst_tunjangan')
            ->get();

        return response()->json([
            'data_gj' => $data_gj,
            'data_tj' => $data_tj,
            'tj' => $tj
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manajemen karyawan - pergerakan karir - data promosi')) {
            return view('roles.forbidden');
        }
        $data = DB::table('demosi_promosi_pangkat')
            ->where('keterangan', 'Promosi')
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
            ->get();

        $data->map(function($promosi) {
            $entity = EntityService::getEntity($promosi->kd_entitas_baru);
            $type = $entity->type;
            $promosi->kantor_baru = '-';

            if($type == 2) $promosi->kantor_baru = "Cab. " . $entity->cab->nama_cabang;
            if($type == 1) {
                if(isset($entity->subDiv)){
                    $promosi->kantor_baru = $entity?->subDiv?->nama_subdivisi . " (Pusat)";
                } else if(isset($entity->div)){
                    $promosi->kantor_baru = $entity?->div?->nama_divisi . " (Pusat)";
                }
            }

            return $promosi;
        });

        $data->map(function($promosiLama) {
            $entityLama = EntityService::getEntity($promosiLama->kd_entitas_lama);
            $typeLama = $entityLama->type;
            $promosiLama->kantor_lama = '-';

            if($typeLama == 2) $promosiLama->kantor_lama = "Cab. " . $entityLama->cab->nama_cabang;
            if($typeLama == 1) {
                if(isset($entityLama->subDiv)){
                    $promosiLama->kantor_lama = $entityLama->subDiv->nama_subdivisi . " (Pusat)";
                } else if(isset($entityLama->div)){
                    $promosiLama->kantor_lama = $entityLama->div->nama_divisi . " (Pusat)";
                }
            }

            return $promosiLama;
        });

        return view('promosi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manajemen karyawan - pergerakan karir - data promosi - create promosi')) {
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

        return view('promosi.add', ['data' => $data, 'jabatan' => $data_panggol, 'tunjangan' => $tj, 'panggol' => $panggol]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $promosi = DB::table('demosi_promosi_pangkat')
            ->insert([
                'nip' => $request->nip,
                'tanggal_pengesahan' => $request->tanggal_pengesahan,
                'bukti_sk' => $request->bukti_sk,
                'keterangan' => 'Promosi',
                'kd_entitas_lama' => $request->kd_entity,
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
            Alert::error('Error', 'Gagal menambahkan data promosi');
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

        Alert::success('Berhasil', 'Berhasil menambahkan data promosi');
        return redirect()->route('promosi.index');
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
