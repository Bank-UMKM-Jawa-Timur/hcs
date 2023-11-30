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
        $cabang = array();
        $cbg = DB::table('mst_cabang')
            ->select('kd_cabang')
            ->get();
        foreach($cbg as $item){
            array_push($cabang, $item->kd_cabang);
        }
        
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

        if(in_array($karyawan->kd_entitas, $cabang)){
            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                ->where('mst_profil_kantor.kd_cabang', $karyawan->kd_entitas)
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                ->first();
            $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                ->where('kd_cabang', $karyawan->kd_entitas)
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                ->first();
        } else {
            $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                ->where('kd_cabang', '000')
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                ->first();
            $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                ->where('kd_cabang', '000')
                ->where('active', 1)
                ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                ->first();
        }
        // $persen_jkk = $hitungan_penambah && $hitungan_penambah->jkk === null ? $hitungan_penambah->jkk : 0;
        if ($hitungan_penambah == null && $hitungan_pengurang == null) {
            $persen_jkk = 0;
            $persen_jht = 0;
            $persen_jkm = 0;
            $persen_kesehatan = 0;
            $persen_jp_penambah = 0;
            $persen_dpp = 0;
            $persen_jp_pengurang = 0;
            $batas_atas = 0;
            $batas_bawah = 0;
            $jp_jan_feb = 0;
            $jp_mar_des = 0;
        }else{
            $persen_jkk = $hitungan_penambah->jkk;
            $persen_jht = $hitungan_penambah->jht;
            $persen_jkm = $hitungan_penambah->jkm;
            $persen_kesehatan = $hitungan_penambah->kesehatan;
            $persen_jp_penambah = $hitungan_penambah->jp;
            $persen_dpp = $hitungan_pengurang->dpp;
            $persen_jp_pengurang = $hitungan_pengurang->jp;
            $batas_atas = $hitungan_penambah->kesehatan_batas_atas;
            $batas_bawah = $hitungan_penambah->kesehatan_batas_bawah;
            $jp_jan_feb = $hitungan_pengurang->jp_jan_feb;
            $jp_mar_des = $hitungan_pengurang->jp_mar_des;
        }
        // $nominal_jp = ($request->get('bulan') < 3) ? $jp_jan_feb : $jp_mar_des;
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
                'tj_transport' => ($data != null) ? $data->tj_transport : 0,
                'tj_pulsa' => ($data != null) ? $data->tj_pulsa : 0,
                'tj_vitamin' => ($data != null) ? $data->tj_vitamin : 0,
                'uang_makan' => ($data != null) ? $data->uang_makan : 0,
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
// return $bonus;
        foreach($total_gaji as $key => $item){
            $nominal_jp = ($key > 1) ? $jp_mar_des : $jp_jan_feb;
            // Get Jamsostek
            if($item > 0){
                $jkk = 0;
                $jht = 0;
                $jkm = 0;
                $kesehatan = 0;
                $jp_penambah = 0;
                if($karyawan->tanggal_penonaktifan == null){
                    $jkk = round(($persen_jkk / 100) * $item);
                    $jht = round(($persen_jht / 100) * $item);
                    $jkm = round(($persen_jkm / 100) * $item);
                    $jp_penambah = round(($persen_jp_penambah / 100) * $item);
                }
    
                if($karyawan->jkn != null){
                    if($item > $batas_atas){
                        $kesehatan = round($batas_atas * ($persen_kesehatan / 100));
                    } else if($item < $batas_bawah){
                        $kesehatan = round($batas_bawah * ($persen_kesehatan / 100));
                    } else{
                        $kesehatan = round($item * ($persen_kesehatan / 100));
                    }
                }
                array_push($jamsostek, ($jkk + $jht + $jkm + $kesehatan + $jp_penambah));
            } else {
                array_push($jamsostek, 0);
            }

            // Get Pengurang Bruto
            if($item > 0){
                if($karyawan->status_karyawan == 'IKJP') {
                    array_push($pengurang, ($persen_jp_pengurang / 100) * $item);
                } else{
                    $gj_pokok = $gj[$key]['gj_pokok'];
                    $tj_keluarga = $gj[$key]['tj_keluarga'];
                    $tj_kesejahteraan = $gj[$key]['tj_kesejahteraan'];

                    $dpp = ((($gj_pokok + $tj_keluarga) + ($tj_kesejahteraan * 0.5)) * ($persen_dpp / 100));
                    if($item >= $nominal_jp){
                        $dppExtra = $nominal_jp * ($persen_jp_pengurang / 100);
                    } else {
                        $dppExtra = $item * ($persen_jp_pengurang / 100);
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
// return [
//     'gj' => $gj,
//     'jamsostek' => $jamsostek,
//     'tunjangan' => $tk,
//     'penghasilan' => $ptt,
//     'bonus' => $bonus,
//     'tahun' => $tahun,
//     'karyawan' => $karyawan,
//     'request' => $request,
//     'mode' => $mode,
//     'pengurang' => array_sum($pengurang),
//     'pph' => $pph_yang_dilunasi
// ];
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
