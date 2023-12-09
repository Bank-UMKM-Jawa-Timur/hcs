<?php

namespace App\Http\Controllers;

use App\Models\MstProfilKantorModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProfilKantorPusatController extends Controller
{
    public function index() {
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
            )
            ->where('mst_cabang.kd_cabang', '000')
            ->leftJoin('mst_profil_kantor AS p', 'p.kd_cabang', 'mst_cabang.kd_cabang')
            ->first();

        return view('profil-kantor-pusat.index', compact('data'));
    }

    public function update(Request $request) {
        try{
            DB::beginTransaction();
            $kode = '000';
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

                DB::table('mst_cabang')->where('kd_cabang', $kode)->update([
                    'alamat_cabang' => $request->alamat_cabang,
                ]);

                DB::table('mst_profil_kantor')->where('kd_cabang', $kode)->update([
                    'masa_pajak' => $request->masa_pajak,
                    'tanggal_lapor' => $request->tanggal_lapor,
                    'npwp_pemotong' => $request->npwp_pemotong,
                    'nama_pemotong' => $request->nama_pemotong,
                    'telp' => $request->telp,
                    'email' => $request->email,
                    'npwp_pemimpin_cabang' => $request->npwp_pemimpin_cabang,
                    'nama_pemimpin_cabang' => $request->nama_pemimpin_cabang,
                ]);
            }
            DB::commit();

            Alert::success('Berhasil', 'Berhasil menyimpan data.');
            return redirect()->route('profil-kantor-pusat.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('profil-kantor-pusat.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('profil-kantor-pusat.index')->withStatus($e->getMessage());
        }
    }
}
