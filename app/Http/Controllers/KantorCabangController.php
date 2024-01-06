<?php

namespace App\Http\Controllers;

use App\Models\MstProfilKantorModel;
use App\Models\PemotongPajakPenguranganModel;
use App\Models\PemotongPajakTambahanModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class KantorCabangController extends Controller
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
        $data = DB::table('mst_cabang')
                ->select(
                    'mst_cabang.*',
                    'p.id AS profil_id',
                    'p.kd_cabang AS kode_cabang_profil',
                )
                ->leftJoin('mst_profil_kantor AS p', 'mst_cabang.kd_cabang', 'p.kd_cabang')
                ->where('mst_cabang.kd_cabang', '!=', '000')
                ->get();

        return view('cabang.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
        return view('cabang.add');
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
            'kode_cabang' => 'required',
            'nama_cabang' => 'required',
            'alamat_cabang' => 'required'
        ], [
            'required' => 'Data harus diisi.'
        ]);

        try{
            DB::table('mst_cabang')
                ->insert([
                    'kd_cabang' => $request->get('kode_cabang'),
                    'nama_cabang' => $request->get('nama_cabang'),
                    'alamat_cabang' => $request->get('alamat_cabang'),
                    'id_kantor' => 2,
                    'created_at' => now()
                ]);

            Alert::success('Berhasil', 'Berhasil Menambah Kantor Cabang.');
            return redirect()->route('cabang.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', 'Kode Cabang Telah Digunakan.');
            return redirect()->route('cabang.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', 'Gagal Menambah Kantor Cabang.');
            return redirect()->route('cabang.index')->withStatus($e->getMessage());
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
        $data = DB::table('mst_cabang')
            ->select(
                'mst_cabang.*',
                'p.masa_pajak',
                'p.tanggal_lapor',
                'p.npwp_pemotong',
                'p.nama_pemotong',
                'p.telp',
                'p.email',
                'p.npwp_pemimpin_cabang',
                'p.nama_pemimpin_cabang',
                'p.id as id_profil'
            )
            ->where('mst_cabang.kd_cabang', $id)
            ->leftJoin('mst_profil_kantor AS p', 'p.kd_cabang', 'mst_cabang.kd_cabang')
            ->first();

        if($data->id_profil != null){
            $data->penambah = PemotongPajakTambahanModel::where('pemotong_pajak_tambahan.id_profil_kantor', $data->id_profil)
                ->select(
                    'pemotong_pajak_tambahan.*'
                )
                ->join('mst_profil_kantor AS p', 'p.id', 'pemotong_pajak_tambahan.id_profil_kantor')
                ->orderBy('active', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();

            $data->pengurang = PemotongPajakPenguranganModel::where('pemotong_pajak_pengurangan.id_profil_kantor', $data->id_profil)
                ->select(
                    'pemotong_pajak_pengurangan.*'
                )
                ->join('mst_profil_kantor AS p', 'p.id', 'pemotong_pajak_pengurangan.id_profil_kantor')
                ->orderBy('active', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();
        } else {
            $data->pengurang = null;
            $data->penambah = null;
        }
        return view('cabang.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode)
    {
        try{
            DB::beginTransaction();

            $this->validate($request, [
                'kode_cabang' => 'required',
                'nama_cabang' => 'required',
                'alamat_cabang' => 'required'
            ], [
                'required' => ':attribute harus diisi.',
            ], [
                'kd_cabang' => 'Cabang',
                'npwp_pemotong' => 'NPWP Pemotong',
                'nama_pemotong' => 'Nama Pemotong',
            ]);
            DB::table('mst_cabang')
                ->where('kd_cabang', $kode)
                ->update([
                    'nama_cabang' => $request->nama_cabang,
                    'alamat_cabang' => $request->alamat_cabang,
                    'id_kantor' => 2,
                    'updated_at' => now()
                ]);
            
            $currentProfil = MstProfilKantorModel::where('kd_cabang', $kode)->first();

            if ($currentProfil) {
                // update current profile
                $isUniqueKdCabang = $request->kd_cabang != null && $request->kd_cabang != $currentProfil->kd_cabang ? '|unique:mst_profil_kantor,kd_cabang' : '';
                $isUniqueNamaPemotong = $request->nama_pemotong != null && $request->nama_pemotong != $currentProfil->nama_pemotong ? '|unique:mst_profil_kantor,nama_pemotong' : '';
                $isUniqueTelp = $request->telp != null && $request->telp != $currentProfil->telp ? '|unique:mst_profil_kantor,kd_cabang' : '';
                $isUniqueEmail = $request->email != null && $request->email != $currentProfil->email ? '|unique:mst_profil_kantor,kd_cabang' : '';
                $isUniqueNpwpPemimpinCabang = $request->npwp_pemimpin_cabang != null && $request->npwp_pemimpin_cabang != $currentProfil->npwp_pemimpin_cabang ? '|unique:mst_profil_kantor,npwp_pemimpin_cabang' : '';
                $isUniqueNamaPemimpinCabang = $request->nama_pemimpin_cabang != null && $request->nama_pemimpin_cabang != $currentProfil->nama_pemimpin_cabang ? '|unique:mst_profil_kantor,nama_pemimpin_cabang' : '';

                $this->validate($request, [
                    'npwp_pemotong' => 'required'.$isUniqueKdCabang,
                    'nama_pemotong' => 'required'.$isUniqueNamaPemotong,
                    'telp' => $isUniqueTelp,
                    'email' => 'required'.$isUniqueEmail,
                    'npwp_pemimpin_cabang' => 'required'.$isUniqueNpwpPemimpinCabang,
                    'nama_pemimpin_cabang' => 'required'.$isUniqueNamaPemimpinCabang,
                ], [
                    'required' => ':attribute harus diisi.',
                    'unique' => ':attribute sudah digunakan.',
                ], [
                    'kd_cabang' => 'Cabang',
                    'npwp_pemotong' => 'NPWP Pemotong',
                    'nama_pemotong' => 'Nama Pemotong',
                    'telp' => 'Telepon',
                    'email' => 'Email',
                    'npwp_pemimpin_cabang' => 'NPWP Pemimpin Cabang',
                    'nama_pemimpin_cabang' => 'Nama Pemimpin Cabang',
                ]);

                $currentProfil->kd_cabang = $kode;
                $currentProfil->masa_pajak = $request->masa_pajak;
                $currentProfil->tanggal_lapor = $request->tanggal_lapor;
                $currentProfil->npwp_pemotong = $request->npwp_pemotong;
                $currentProfil->nama_pemotong = $request->nama_pemotong;
                $currentProfil->telp = $request->telp;
                $currentProfil->email = $request->email;
                $currentProfil->npwp_pemimpin_cabang = $request->npwp_pemimpin_cabang;
                $currentProfil->nama_pemimpin_cabang = $request->nama_pemimpin_cabang;
                $currentProfil->save();

                $pengurang = PemotongPajakPenguranganModel::where('id_profil_kantor', $currentProfil->id)->first();
                $penambah = PemotongPajakTambahanModel::where('id_profil_kantor', $currentProfil->id)->first();
                
                if($pengurang && date('Y', strtotime($pengurang?->created_at) == date('Y', strtotime(now())))){
                    $pengurang->dpp = $request->dpp;
                    $pengurang->jp_jan_feb = str_replace('.', '',$request->jp_jan_feb);
                    $pengurang->jp_mar_des = str_replace('.', '',$request->jp_mar_des);
                    $pengurang->save();
                } else {
                    PemotongPajakPenguranganModel::insert([
                        'id_profil_kantor' => $currentProfil->id,
                        'dpp' => $request->dpp,
                        'jp_jan_feb' => str_replace('.', '', $request->jp_jan_feb),
                        'jp_mar_des' => str_replace('.', '', $request->jp_mar_des),
                        'created_at' => now()
                    ]);
                }

                if($penambah && date('Y', strtotime($penambah?->created_at) == date('Y', strtotime(now())))){
                    $penambah->jkk = $request->jkk;
                    $penambah->jht = $request->jht;
                    $penambah->jkm = $request->jkm;
                    $penambah->kesehatan = $request->kesehatan;
                    $penambah->kesehatan_batas_atas = str_replace('.', '', $request->kesehatan_batas_atas);
                    $penambah->kesehatan_batas_bawah = str_replace('.', '', $request->kesehatan_batas_bawah);
                    $penambah->jp = $request->jp;
                    $penambah->total = $request->total;
                } else {
                    PemotongPajakTambahanModel::insert([
                        'id_profil_kantor' => $currentProfil->id,
                        'jkk' => $request->jkk,
                        'jht' => $request->jht,
                        'jkm' => $request->jkm,
                        'kesehatan' => $request->kesehatan,
                        'kesehatan_batas_atas' => str_replace('.', '', $request->kesehatan_batas_atas),
                        'kesehatan_batas_bawah' => str_replace('.', '', $request->kesehatan_batas_bawah),
                        'jp' => $request->jp,
                        'total' => $request->total,
                        'created_at' => now()
                    ]);
                }
            }
            else {
                // create new profile
                $this->validate($request, [
                    'kode_cabang' => 'required|unique:mst_profil_kantor,kd_cabang',
                    'npwp_pemotong' => 'required|unique:mst_profil_kantor,npwp_pemotong',
                    'nama_pemotong' => 'required|unique:mst_profil_kantor,nama_pemotong',
                    'telp' => 'unique:mst_profil_kantor,telp',
                    'email' => 'required|unique:mst_profil_kantor,email',
                    'npwp_pemimpin_cabang' => 'required|unique:mst_profil_kantor,npwp_pemimpin_cabang',
                    'nama_pemimpin_cabang' => 'required|unique:mst_profil_kantor,nama_pemimpin_cabang',
                ], [
                    'required' => ':attribute harus diisi.',
                    'unique' => ':attribute sudah digunakan.',
                ], [
                    'kd_cabang' => 'Cabang',
                    'npwp_pemotong' => 'NPWP Pemotong',
                    'nama_pemotong' => 'Nama Pemotong',
                    'telp' => 'Telepon',
                    'email' => 'Email',
                    'npwp_pemimpin_cabang' => 'NPWP Pemimpin Cabang',
                    'nama_pemimpin_cabang' => 'Nama Pemimpin Cabang',
                ]);
                
                $newProfil = new MstProfilKantorModel();
                $newProfil->kd_cabang = $kode;
                $newProfil->masa_pajak = $request->masa_pajak;
                $newProfil->tanggal_lapor = $request->tanggal_lapor;
                $newProfil->npwp_pemotong = $request->npwp_pemotong;
                $newProfil->nama_pemotong = $request->nama_pemotong;
                $newProfil->telp = $request->telp;
                $newProfil->email = $request->email;
                $newProfil->npwp_pemimpin_cabang = $request->npwp_pemimpin_cabang;
                $newProfil->nama_pemimpin_cabang = $request->nama_pemimpin_cabang;
                $newProfil->save();
                $profilId = $newProfil->id;

                PemotongPajakTambahanModel::insert([
                    'id_profil_kantor' => $profilId,
                    'jkk' => $request->jkk,
                    'jht' => $request->jht,
                    'jkm' => $request->jkm,
                    'kesehatan' => $request->kesehatan,
                    'kesehatan_batas_atas' => str_replace('.', '', $request->kesehatan_batas_atas),
                    'kesehatan_batas_bawah' => str_replace('.', '', $request->kesehatan_batas_bawah),
                    'jp' => $request->jp,
                    'total' => $request->total,
                    'created_at' => now()
                ]);
                PemotongPajakPenguranganModel::insert([
                    'id_profil_kantor' => $profilId,
                    'dpp' => $request->dpp,
                    'jp_jan_feb' => str_replace('.', '', $request->jp_jan_feb),
                    'jp_mar_des' => str_replace('.', '', $request->jp_mar_des),
                    'created_at' => now()
                ]);
            }

            DB::commit();
            Alert::success('Berhasil', 'Berhasil menyimpan perubahan.');
            return redirect()->route('cabang.index');
        } catch(Exception $e){
            DB::rollBack();

            Alert::error('Terjadi kesalahan.', $e->getMessage());
            return redirect()->route('cabang.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            DB::rollBack();

            Alert::error('Terjadi kesalahan pada database.', $e->getMessage());
            return redirect()->route('cabang.index')->withStatus($e->getMessage());
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
            DB::table('mst_cabang')
                ->where('kd_cabang', $id)
                ->delete();

            Alert::success('Berhasil', 'Berhasil Menghapus Kantor Cabang.');
            return redirect()->route('cabang.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('cabang.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('cabang.index')->withStatus($e->getMessage());
        }
    }
}
