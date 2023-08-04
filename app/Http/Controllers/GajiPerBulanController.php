<?php

namespace App\Http\Controllers;

use App\Models\GajiPerBulanModel;
use App\Models\PPHModel;
use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class GajiPerBulanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getBulan(Request $request)
    {
        $tahun = $request->get('tahun');

        $bulan = DB::table('gaji_per_bulan')
            ->where('tahun', $tahun)
            ->distinct()
            ->get('bulan');
        if(count($bulan) > 0){
            return response()->json($bulan);
        } else{
            return null;
        }
    }

    public function index()
    {
        $data = DB::table('gaji_per_bulan')
            ->selectRaw('DISTINCT(bulan), tahun')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('gaji_perbulan.index', ['data_gaji' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $cabang = array();
            $cbg = DB::table('mst_cabang')
                ->select('kd_cabang')
                ->get();
            foreach($cbg as $item){
                array_push($cabang, $item->kd_cabang);
            }

            DB::beginTransaction();
            $employee = array();
            $pph = array();
            $tj_jamsostek = array();
            $tunjangan = array();
            $karyawan = DB::table('mst_karyawan')
                ->whereNull('tanggal_penonaktifan')
                ->get();

            foreach($karyawan as $item){
                unset($tunjangan);
                unset($tj_jamsostek);
                $tj_jamsostek = array();
                $tunjangan = array();
                for($i = 1; $i <= 15; $i++){
                    $tj = DB::table('tunjangan_karyawan')
                        ->where('nip', $item->nip)
                        ->where('id_tunjangan', $i)
                        ->first();
                    array_push($tunjangan, ($tj != null) ? $tj->nominal : 0);
                    if($i >= 1 && $i <= 9){
                        array_push($tj_jamsostek, ($tj != null) ? $tj->nominal : 0);
                    }
                }

                $penghasilan_tidak_teratur = DB::table('penghasilan_tidak_teratur')
                    ->where('nip', $item->nip)
                    ->where('bulan', $request->get('bulan'))
                    ->where('tahun', $request->get('tahun'))
                    ->whereDate('created_at', '<=', '25')
                    ->sum('nominal');
                $total_gaji = $penghasilan_tidak_teratur + $item->gj_pokok + $item->gj_penyesuaian + (array_sum($tunjangan) - $tunjangan[14]);
                // Gaji Untuk Jamsostek
                $gj_jamsostek = $item->gj_pokok + $item->gj_penyesuaian + array_sum($tj_jamsostek);
                $status = 'TK';
                if ($item->status == 'K' || $item->status == 'Kawin') {
                    $anak = DB::table('mst_karyawan')
                        ->where('keluarga.nip', $item->nip)
                        ->join('keluarga', 'keluarga.nip', 'mst_karyawan.nip')
                        ->whereIn('enum', ['Suami', 'Istri'])
                        ->first('jml_anak');
                    if ($anak != null && $anak->jml_anak > 3) {
                        $status = 'K/3';
                    } else if ($anak != null) {
                        $status = 'K/'.$anak->jml_anak;
                    } else {
                        $status = 'K/0';
                    }
                }
                $ptkp = DB::table('set_ptkp')
                    ->where('kode', $status)
                    ->first();

                $bonus_sum = DB::table('penghasilan_tidak_teratur')
                    ->where('bulan', $request->get('bulan'))
                    ->where('tahun', $request->get('tahun'))
                    ->where('nip', $item->nip)
                    ->where('id', '>=', '22')
                    ->where('id', '<=', '24')
                    ->orWhere('id', '26')
                    ->sum('nominal');

                // Perhitungan Pajak
                $jkk = 0;
                $jht = 0;
                $jkm = 0;
                $kesehatan = 0;
                $jp_penambah = 0;
                $nominal_jp = 0;

                if(in_array($item->kd_entitas, $cabang)){
                    $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->where('active', 1)
                        ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                        ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                        ->first();
                    $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                        ->where('kd_cabang', $item->kd_entitas)
                        ->where('active', 1)
                        ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                        ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                        ->first();
                } else {
                    $hitungan_penambah = DB::table('pemotong_pajak_tambahan')
                        ->whereNull('kd_cabang')
                        ->where('active', 1)
                        ->join('mst_profil_kantor', 'pemotong_pajak_tambahan.id_profil_kantor', 'mst_profil_kantor.id')
                        ->select('jkk', 'jht', 'jkm', 'kesehatan', 'kesehatan_batas_atas', 'kesehatan_batas_bawah', 'jp', 'total')
                        ->first();
                    $hitungan_pengurang = DB::table('pemotong_pajak_pengurangan')
                        ->whereNull('kd_cabang')
                        ->where('active', 1)
                        ->join('mst_profil_kantor', 'pemotong_pajak_pengurangan.id_profil_kantor', 'mst_profil_kantor.id')
                        ->select('dpp', 'jp', 'jp_jan_feb', 'jp_mar_des')
                        ->first();
                }
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
                $nominal_jp = ($request->get('bulan') < 3) ? $jp_jan_feb : $jp_mar_des;

                // Pengurang Bruto Jamsostek
                $pengurang = 0;
                if($item->status_karyawan == 'IKJP'){
                    $pengurang = ($persen_jp_pengurang / 100) * $gj_jamsostek;
                } else{
                    $dpp = ((($item->gj_pokok + $tunjangan[0]) + ($tunjangan[7] * 0.5)) * 0.05);
                    if($gj_jamsostek >= $nominal_jp){
                        $dppExtra = $nominal_jp * ($persen_jp_pengurang / 100);
                    } else {
                        $dppExtra = $gj_jamsostek * ($persen_jp_pengurang / 100);
                    }
                    $pengurang = round($dpp + $dppExtra);
                }

                if($item->tanggal_penonaktifan == null){
                    $jkk = round(($persen_jkk / 100) * $gj_jamsostek);
                    $jht = round(($persen_jht / 100) * $gj_jamsostek);
                    $jkm = round(($persen_jkm / 100) * $gj_jamsostek);
                    $jp_penambah = round(($persen_jp_penambah / 100) * $gj_jamsostek); 
                }
    
                if($item->jkn != null){
                    if($gj_jamsostek > $batas_atas){
                        $kesehatan = round($batas_atas * ($persen_kesehatan / 100));
                    } else if($gj_jamsostek < $batas_bawah){
                        $kesehatan = round($batas_bawah * ($persen_kesehatan / 100));
                    } else{
                        $kesehatan = round($gj_jamsostek * ($persen_kesehatan / 100));
                    }
                }
                $total_gaji += $bonus_sum + ($jkk + $jht + $jkm + $kesehatan + $jp_penambah);
                $lima_persen = round(0.05 * $total_gaji);
                $keterangan = 500000;
                $biaya_jabatan = 0;
                $no_14 = 0;

                if($lima_persen > $keterangan){
                    $biaya_jabatan = $keterangan;
                } else {
                    $biaya_jabatan = $lima_persen;
                }
                $rumus_14 = 0;
                if (0.05 * $total_gaji > $keterangan) {
                    $rumus_14 = round($keterangan);
                } else{
                    $rumus_14 = round(0.05 * ($total_gaji));
                }
                $no_14 = ((($total_gaji - $bonus_sum - $pengurang - $biaya_jabatan) / 1) * 12) + $bonus_sum + ($biaya_jabatan - $rumus_14);
                $persen5 = 0;
                if (($no_14 - $ptkp?->ptkp_tahun) > 0) {
                    if (($no_14 - $ptkp?->ptkp_tahun) <= 60000000) {
                        $persen5 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.05 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.06;
                    } else {
                        $persen5 = ($item->npwp != null) ? 60000000 * 0.05 : 60000000 * 0.06;
                    }
                } else {
                    $persen5 = 0;
                }
                $persen15 = 0;
                if (($no_14 - $ptkp?->ptkp_tahun) > 60000000) {
                    if (($no_14 - $ptkp?->ptkp_tahun) <= 250000000) {
                        $persen15 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.15 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000- 60000000) * 0.18;
                    } else {
                        $persen15 = 190000000;
                    }
                } else {
                    $persen20 = 0;
                }
                $persen25 = 0;
                if (($no_14 - $ptkp?->ptkp_tahun) > 250000000) {
                    if (($no_14 - $ptkp?->ptkp_tahun) <= 500000000) {
                        $persen25 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.25 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.3;
                    } else {
                        $persen25 = 250000000;
                    }
                } else {
                    $persen25 = 0;
                }
                $persen30 = 0;
                if (($no_14 - $ptkp?->ptkp_tahun) > 250000000) {
                    if (($no_14 - $ptkp?->ptkp_tahun) <= 500000000) {
                        $persen30 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.3 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.36;
                    } else {
                        $persen30 = 4500000000;
                    }
                } else {
                    $persen30 = 0;
                }
                $persen35 = 0;
                if (($no_14 - $ptkp?->ptkp_tahun) > 500000000) {
                        $persen35 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.35 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.42;
                } else {
                    $persen35 = 0;
                }

                $no17 = (($persen5 + $persen15 + $persen25 + $persen30 + $persen35) / 1000) * 1000;
                $no19 = ($no17 / 12);
                
                if($request->get('bulan') != 1){
                    $penghasilan_tidak_teratur = DB::table('penghasilan_tidak_teratur')
                        ->where('nip', $item->nip)
                        ->where('bulan', $request->get('bulan') - 1)
                        ->where('tahun', $request->get('tahun'))
                        ->sum('nominal');
                    $total_gaji = $penghasilan_tidak_teratur + $item->gj_pokok + $item->gj_penyesuaian + (array_sum($tunjangan) - $tunjangan[14]);
                    // Gaji Untuk Jamsostek
                    $gj_jamsostek = $item->gj_pokok + $item->gj_penyesuaian + array_sum($tj_jamsostek);
                    $status = 'TK';
                    if ($item->status == 'K' || $item->status == 'Kawin') {
                        $anak = DB::table('mst_karyawan')
                            ->where('keluarga.nip', $item->nip)
                            ->join('keluarga', 'keluarga.nip', 'mst_karyawan.nip')
                            ->whereIn('enum', ['Suami', 'Istri'])
                            ->first('jml_anak');
                        if ($anak != null && $anak->jml_anak > 3) {
                            $status = 'K/3';
                        } else if ($anak != null) {
                            $status = 'K/'.$anak->jml_anak;
                        } else {
                            $status = 'K/0';
                        }
                    }
                    $ptkp = DB::table('set_ptkp')
                        ->where('kode', $status)
                        ->first();
                    $pengurang = 0;
                    if($item->status_karyawan == 'IKJP'){
                        $pengurang = ($persen_jp_pengurang / 100) * $gj_jamsostek;
                    } else{
                        $dpp = ((($item->gj_pokok + $tunjangan[0]) + ($tunjangan[7] * 0.5)) * 0.05);
                        if($gj_jamsostek >= $nominal_jp){
                            $dppExtra = $nominal_jp * ($persen_jp_pengurang / 100);
                        } else {
                            $dppExtra = $gj_jamsostek * ($persen_jp_pengurang / 100);
                        }
                        $pengurang = round($dpp + $dppExtra);
                    }
                    $bonus_sum = DB::table('penghasilan_tidak_teratur')
                        ->where('bulan', $request->get('bulan') - 1)
                        ->where('tahun', $request->get('tahun'))
                        ->where('nip', $item->nip)
                        ->where('id', '>=', '22')
                        ->where('id', '<=', '24')
                        ->orWhere('id', '26')
                        ->sum('nominal');
                    $jkk = 0;
                    $jht = 0;
                    $jkm = 0;
                    $kesehatan = 0;
                    $jp_penambah = 0;
                    if($item->tanggal_penonaktifan == null){
                        $jkk = round(($persen_jkk / 100) * $gj_jamsostek);
                        $jht = round(($persen_jht / 100) * $gj_jamsostek);
                        $jkm = round(($persen_jkm / 100) * $gj_jamsostek);
                        $jp_penambah = round(($persen_jp_penambah / 100) * $gj_jamsostek); 
                    }
        
                    if($item->jkn != null){
                        if($gj_jamsostek > $batas_atas){
                            $kesehatan = round($batas_atas * ($persen_kesehatan / 100));
                        } else if($gj_jamsostek < $batas_bawah){
                            $kesehatan = round($batas_bawah * ($persen_kesehatan / 100));
                        } else{
                            $kesehatan = round($gj_jamsostek * ($persen_kesehatan / 100));
                        }
                    }
                    $total_gaji += $bonus_sum + ($jkk + $jht + $jkm + $kesehatan + $jp_penambah);
                    $lima_persen = round(0.05 * $total_gaji);
                    $keterangan = 500000;
                    $biaya_jabatan = 0;
                    $no_14 = 0;

                    if($lima_persen > $keterangan){
                        $biaya_jabatan = $keterangan;
                    } else {
                        $biaya_jabatan = $lima_persen;
                    }
                    $rumus_14 = 0;
                    if (0.05 * $total_gaji > $keterangan) {
                        $rumus_14 = round($keterangan);
                    } else{
                        $rumus_14 = round(0.05 * ($total_gaji));
                    }
                    $no_14 = ((($total_gaji - $bonus_sum - $pengurang - $biaya_jabatan) / 1) * 12) + $bonus_sum + ($biaya_jabatan - $rumus_14);
                    $persen5 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 0) {
                        if (($no_14 - $ptkp?->ptkp_tahun) <= 60000000) {
                            $persen5 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.05 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000) * 0.06;
                        } else {
                            $persen5 = ($item->npwp != null) ? 60000000 * 0.05 : 60000000 * 0.06;
                        }
                    } else {
                        $persen5 = 0;
                    }
                    $persen15 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 60000000) {
                        if (($no_14 - $ptkp?->ptkp_tahun) <= 250000000) {
                            $persen15 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 60000000) * 0.15 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000- 60000000) * 0.18;
                        } else {
                            $persen15 = 190000000;
                        }
                    } else {
                        $persen20 = 0;
                    }
                    $persen25 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 250000000) {
                        if (($no_14 - $ptkp?->ptkp_tahun) <= 500000000) {
                            $persen25 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.25 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 250000000) * 0.3;
                        } else {
                            $persen25 = 250000000;
                        }
                    } else {
                        $persen25 = 0;
                    }
                    $persen30 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 250000000) {
                        if (($no_14 - $ptkp?->ptkp_tahun) <= 500000000) {
                            $persen30 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.3 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.36;
                        } else {
                            $persen30 = 4500000000;
                        }
                    } else {
                        $persen30 = 0;
                    }
                    $persen35 = 0;
                    if (($no_14 - $ptkp?->ptkp_tahun) > 500000000) {
                            $persen35 = ($item->npwp != null) ? (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.35 :  (floor(($no_14 - $ptkp?->ptkp_tahun) / 1000) * 1000 - 500000000) * 0.42;
                    } else {
                        $persen35 = 0;
                    }

                    $no17 = (($persen5 + $persen15 + $persen25 + $persen30 + $persen35) / 1000) * 1000;
                    $no19_lama = ($no17 / 12);
                    $pph_lama = DB::table('pph_yang_dilunasi')
                        ->where('bulan', $request->get('bulan') - 1)
                        ->where('tahun', $request->get('tahun'))
                        ->where('nip', $item->nip)
                        ->first();
                    $cek_ptt = DB::table('penghasilan_tidak_teratur')
                        ->where('nip', $item->nip)
                        ->where('bulan', $request->get('bulan') - 1)
                        ->where('tahun', $request->get('tahun'))
                        ->whereDate('created_at', '>', '25')
                        ->sum('nominal');
                    
                    $no19 = ($cek_ptt > 0) ? (float)$no19 + ((float)$no19_lama - (float)$pph_lama->total_pph) : $no19;
                }

                array_push($employee, [
                    'nip' => $item->nip,
                    'bulan' => $request->get('bulan'),
                    'tahun' => $request->get('tahun'),
                    'gj_pokok' => $item->gj_pokok,
                    'gj_penyesuaian' => $item->gj_penyesuaian,
                    'tj_keluarga' => $tunjangan[0],
                    'tj_telepon' => $tunjangan[1],
                    'tj_jabatan' => $tunjangan[2],
                    'tj_teller' => $tunjangan[3],
                    'tj_perumahan' => $tunjangan[4],
                    'tj_kemahalan' => $tunjangan[5],
                    'tj_pelaksana' => $tunjangan[6],
                    'tj_kesejahteraan' => $tunjangan[7],
                    'tj_multilevel' => $tunjangan[8],
                    'tj_ti' => $tunjangan[9],
                    'tj_transport' => $tunjangan[10],
                    'tj_pulsa' => $tunjangan[11],
                    'tj_vitamin' => $tunjangan[12],
                    'uang_makan' => $tunjangan[13],
                    'dpp' => $tunjangan[14],
                    'created_at' => now()
                ]);

                array_push($pph, [
                    'nip' => $item->nip,
                    'bulan' => $request->get('bulan'),
                    'tahun' => $request->get('tahun'),
                    'total_pph' => round($no19),
                    'tanggal' => now(),
                    'created_at' => now()
                ]);
            }
            GajiPerBulanModel::insert($employee);
            PPHModel::insert($pph);
            DB::commit();
            Alert::success('Berhasil', 'Berhasil Melakukan Pembayaran Gaji Karyawan.');
            return redirect()->route('gaji_perbulan.index');
        }catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('gaji_perbulan.index');
        }catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('gaji_perbulan.index');
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
