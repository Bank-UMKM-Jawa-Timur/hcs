<?php

namespace App\Http\Controllers;

use App\Imports\PenghasilanImport;
use App\Models\PPHModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class PenghasilanTidakTeraturController extends Controller
{
    public function getDataKaryawan(Request $request)
    {
        $nip = $request->get('nip');
        $data = DB::table('mst_karyawan')
            ->where('nip', $nip)
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->select('mst_karyawan.*', 'mst_jabatan.nama_jabatan')
            ->first();
        if($data == null){
            return null;
        }
        if($data->status_karyawan == "Tetap"){
            $jabatan = "Pegawai ".$data->status_karyawan . ' ' . $data->nama_jabatan;
        }
        else{
            $jabatan = $data->status_karyawan . ' ' . $data->nama_jabatan;
        }
        $dk = [
            'nip' => $data->nip,
            'nama' => $data->nama_karyawan,
            'jabatan' => $jabatan
        ];

        return response()->json($dk);
    }

    public function upload(Request $request)
    {
        $file = $request->file('upload_csv');
        $import = new PenghasilanImport;
        $row = Excel::toArray($import, $file);
        // dd(count($row[0]));
        $import = $import->import($file);
        Alert::success('Berhasil', 'Berhasil mengimport '.count($row[0]).' data');
        return redirect()->route('import-penghasilan-index');
    }

    public function filter(Request $request)
    {
        $tahun = $request->get('tahun');
        $mode = $request->get('mode');
        $nip = $request->get('nip');
        $gaji = array();
        $total_gaji = array();
        $tk = array();
        $ptt = array();
        $bonus = array();
        $total_gj = array();
        $jamsostek = array();
        $pengurang = array();
        $pph_yang_dilunasi = array();
        $tj = [];
        $peng = [];
        $bon = [];
        $w = 0;
        $x = 0;
        $y = 0;
        $z = 0;

        $karyawan = DB::table('mst_karyawan')
            ->where(compact('nip'))
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->select('mst_karyawan.*', 'mst_jabatan.nama_jabatan')
            ->first();

        // Get gaji secara bulanan
        for($i = 1; $i <= 12; $i++){
            $pph = PPHModel::where('nip', $nip)
                ->where('bulan', $i)
                ->where('tahun', $tahun)
                ->first();
            array_push($pph_yang_dilunasi, ($pph != null) ? $pph->total_pph : 0);
            $data = DB::table('gaji_per_bulan')
                ->where('nip', $nip)
                ->where('bulan', $i)
                ->where('tahun', $tahun)
                ->first();
            $tj_trans =  DB::table('penghasilan_tidak_teratur')
                ->where('nip', $nip)
                ->where('id_tunjangan', 11)
                ->where('tahun', $tahun)
                ->where('bulan', $i)
                ->first();
            $tj_pulsa =  DB::table('penghasilan_tidak_teratur')
                ->where('nip', $nip)
                ->where('id_tunjangan', 12)
                ->where('tahun', $tahun)
                ->where('bulan', $i)
                ->first();
            $tj_vitamin =  DB::table('penghasilan_tidak_teratur')
                ->where('nip', $nip)
                ->where('id_tunjangan', 13)
                ->where('tahun', $tahun)
                ->where('bulan', $i)
                ->first();
            $tj_uang_makan =  DB::table('penghasilan_tidak_teratur')
                ->where('nip', $nip)
                ->where('id_tunjangan', 14)
                ->where('tahun', $tahun)
                ->where('bulan', $i)
                ->first();
           $gj[$i - 1] = [
            'gj_pokok' => ($data != null) ? $data->gj_pokok : 0,
            'gj_penyesuaian' => ($data != null) ? $data->gj_penyesuaian : 0,
            'tj_keluarga' => ($data != null) ? $data->tj_keluarga : 0,
            'tj_telepon' => ($data != null) ? $data->tj_telepon : 0,
            'tj_jabatan' => ($data != null) ? $data->tj_jabatan : 0,
            'tj_teller' => ($data != null) ? $data->tj_teller : 0,
            'tj_perumahan' => ($data != null) ? $data->tj_perumahan : 0,
            'tj_kemahalan' => ($data != null) ? $data->tj_kemahalan : 0,
            'tj_pelaksana' => ($data != null) ? $data->tj_pelaksana : 0,
            'tj_kesejahteraan' => ($data != null) ? $data->tj_kesejahteraan : 0,
            'tj_multilevel' => ($data != null) ? $data->tj_multilevel : 0,
            'tj_ti' => ($data != null) ? $data->tj_ti : 0,
            'tj_transport' => ($tj_trans != null && $data != null) ? $tj_trans->nominal : 0,
            'tj_pulsa' => ($tj_pulsa != null && $data != null) ? $tj_pulsa->nominal : 0,
            'tj_vitamin' => ($tj_vitamin != null && $data != null) ? $tj_vitamin->nominal : 0,
            'uang_makan' => ($tj_uang_makan != null && $data != null) ? $tj_uang_makan->nominal : 0,
           ];

           $total_gj[$i-1] = [
            'gj_pokok' => ($data != null) ? $data->gj_pokok : 0,
            'tj_keluarga' => ($data != null) ? $data->tj_keluarga : 0,
            'tj_jabatan' => ($data != null) ? $data->tj_jabatan : 0,
            'gj_penyesuaian' => ($data != null) ? $data->gj_penyesuaian : 0,
            'tj_perumahan' => ($data != null) ? $data->tj_perumahan : 0,
            'tj_telepon' => ($data != null) ? $data->tj_telepon : 0,
            'tj_pelaksana' => ($data != null) ? $data->tj_pelaksana : 0,
            'tj_kemahalan' => ($data != null) ? $data->tj_kemahalan : 0,
            'tj_kesejahteraan' => ($data != null) ? $data->tj_kesejahteraan : 0,
           ];
           array_push($gaji, $gj[$i-1]);
           array_push($total_gaji, array_sum($total_gj[$i-1]));
        // Get Penghasilan tidak teratur karyawan
           $k = 0;
            for($j = 16; $j <= 26; $j++){
                if($j != 22 && $j != 23 && $j != 24 && $j != 26){
                    $penghasilan = DB::table('penghasilan_tidak_teratur')
                        ->where('nip', $nip)
                        ->where('id_tunjangan', $j)
                        ->where('tahun', $tahun)
                        ->where('bulan', $i)
                        ->first();
                    $peng[$k] = ($penghasilan != null) ? $penghasilan->nominal : 0;
                    $k++;
                }
            }
            array_push($ptt, $peng);

            // Get Bonus Karyawan
            $l = 0;
            for($j = 22; $j <= 24; $j++){
                $bns = DB::table('penghasilan_tidak_teratur')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', $j)
                    ->where('tahun', $tahun)
                    ->where('bulan', $i)
                    ->first();
                $bon[$l] = ($bns != null) ? $bns->nominal : 0;
                $l++;
            }
            $bns = DB::table('penghasilan_tidak_teratur')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', 26)
                    ->where('tahun', $tahun)
                    ->where('bulan', $i)
                    ->first();
            $bon[$l] = ($bns != null) ? $bns->nominal : 0;
            $l++;
            array_push($bonus, $bon);
        }

        foreach($total_gaji as $key => $item){
            // Get Jamsostek
            if($item > 0){
                $jkk = 0;
                $jht = 0;
                $jkm = 0;
                $kesehatan = 0;
                if($karyawan->tanggal_penonaktifan == null){
                    $jkk = round(0.0024 * $item);
                    $jht = round(0.0 * $item);
                    $jkm = round(0.0030 * $item);
                }
    
                if($karyawan->jkn != null){
                    if($item > 12000000){
                        $kesehatan = round(12000000 * 0.05);
                    } else if($item < 4375479){
                        $kesehatan = round(4375479 * 0.05);
                    } else{
                        $kesehatan = round($item * 0.05);
                    }
                }
                array_push($jamsostek, ($jkk + $jht + $jkm + $kesehatan));
            } else {
                array_push($jamsostek, 0);
            }

            // Get Pengurang Bruto
            if($item > 0){
                if($karyawan->status_karyawan == 'IKJP') {
                    array_push($pengurang, 0.01 * $item);
                } else{
                    $gj_pokok = $gj[$key]['gj_pokok'];
                    $tj_keluarga = $gj[$key]['tj_keluarga'];
                    $tj_kesejahteraan = $gj[$key]['tj_kesejahteraan'];

                    $dpp = ((($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * 0.05);
                    if($item >= 8754600){
                        $dppExtra = 8754600 * 0.01;
                    } else {
                        $dppExtra = $item * 0.01;
                    }
                    // dd($gj_pokok, $tj_keluarga, $tj_kesejahteraan, $dpp, $dppExtra, $dpp + $dppExtra);
                    array_push($pengurang, round($dpp + $dppExtra));
                }
            } else {
                array_push($pengurang, 0);
            }
        }
        $karyawanController = new KaryawanController;
        $karyawan->masa_kerja = $karyawanController->countAge($karyawan->tanggal_pengangkat);

        return view('penghasilan.gajipajak', [
            'gj' => $gj,
            'jamsostek' => $jamsostek,
            'tunjangan' => $tk,
            'penghasilan' => $ptt,
            'bonus' => $bonus,
            'tahun' => $tahun,
            'karyawan' => $karyawan,
            'request' => $request,
            'mode' => $mode,
            'pengurang' => array_sum($pengurang),
            'pph' => $pph_yang_dilunasi
        ]);
    }

    public function import() {
        return view('penghasilan.import');
    }

    public function insertPenghasilan(Request $request) {
        try{
            DB::beginTransaction();
            $bulan = date('m', strtotime($request->tanggal));
            $tahun = date('Y', strtotime($request->tanggal));

            DB::table('penghasilan_tidak_teratur')
                ->insert([
                    'nip' => $request->nip,
                    'id_tunjangan' => $request->id_tunjangan,
                    'nominal' => str_replace('.', '', $request->nominal),
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'created_at' => $request->tanggal
                ]);

            DB::commit();
            Alert::success('Berhasil', 'Berhasil menambahkan data.');
            return redirect()->route('pajak_penghasilan.create');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan.'.$e->getMessage());
            return redirect()->route('pajak_penghasilan.create');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan.'.$e->getMessage());
            return redirect()->route('pajak_penghasilan.create');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('penghasilan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = DB::table('mst_tunjangan')
            ->where('id', '>', '15')
            ->get();

        return view('penghasilan.add', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $nip = $request->nip;
        try{
            if($request->get('nominal_teratur')[0] != null){
                for($i = 0; $i < count($request->get('nominal_teratur')); $i++){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $nip,
                            'id_tunjangan' => $request->get('id_teratur')[$i],
                            'nominal' => $request->get('nominal_teratur')[$i],
                            'created_at' => now()
                        ]);
                }
            }
            if($request->get('nominal_tidak_teratur')[0] != null){
                for($i = 0; $i < count($request->get('nominal_tidak_teratur')); $i++){
                    DB::table('penghasilan_tidak_teratur')
                        ->insert([
                            'nip' => $nip,
                            'id_tunjangan' => $request->get('id_tidak_teratur')[$i],
                            'nominal' => $request->get('nominal_tidak_teratur')[$i],
                            'bulan' => $request->get('bulan'),
                            'tahun' => $request->get('bulan'),
                            'created_at' => now()
                        ]);
                }
            }
            if($request->get('nominal_bonus')[0] != null){
                for($i = 0; $i < count($request->get('nominal_bonus')); $i++){
                    DB::table('penghasilan_tidak_teratur')
                        ->insert([
                            'nip' => $nip,
                            'id_tunjangan' => $request->get('id_bonus')[$i],
                            'nominal' => $request->get('nominal_bonus')[$i],
                            'bulan' => $request->get('bulan'),
                            'tahun' => $request->get('bulan'),
                            'created_at' => now()
                        ]);
                }
            }

            Alert::success('Berhasil', 'Berhasil menambahkan data penghasilan');
            return redirect()->route('penghasilan.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('penghasilan.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('penghasilan.index');
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
