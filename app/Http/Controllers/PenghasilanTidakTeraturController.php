<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PenghasilanTidakTeraturController extends Controller
{
    public function getDataKaryawan(Request $request)
    {
        $nip = $request->get('nip');
        $karyawan = DB::table('mst_karyawan')
            ->where('nip', $nip)
            ->first();
        return response()->json($karyawan);
    }

    public function filter(Request $request)
    {
        $tahun = $request->get('tahun');
        $nip = $request->get('nip');
        $gj_pokok = array();
        $gj_penyesuaian = array();
        $tk = array();
        $ptt = array();
        $bonus = array();
        $total_tj = array();
        $jamsostek = array();
        $tj = [];
        $peng = [];
        $bon = [];
        $w = 0;
        $x = 0;
        $y = 0;
        $z = 0;

        // Get gaji karyawan selama setahun
        for($i = 1; $i <= 12; $i++){
            $pokok_penyesuaian = DB::table('history_penyesuaian_gaji')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', null)
                    ->where('keterangan', 'Penyesuaian Gaji Pokok')
                    ->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $i)
                    ->first();
            if($i == 1){
                $tes = DB::table('history_penyesuaian_gaji')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', null)
                    ->where('keterangan', 'Penyesuaian Gaji Pokok')
                    ->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $i)
                    ->first();
                $x = $i;
            }
            elseif($i>1){
                if ($pokok_penyesuaian == []) {
                    $tes = DB::table('history_penyesuaian_gaji')
                        ->where('nip', $nip)
                        ->where('id_tunjangan', null)
                        ->where('keterangan', 'Penyesuaian Gaji Pokok')
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $x)
                        ->first();
                } else {
                    $tes = DB::table('history_penyesuaian_gaji')
                        ->where('nip', $nip)
                        ->where('id_tunjangan', null)
                        ->where('keterangan', 'Penyesuaian Gaji Pokok')
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $i)
                        ->first();
                    $x = $i;
                }
                
            }

            // Get Gaji penyesuaian 
            $penyesuaian = DB::table('history_penyesuaian_gaji')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', null)
                    ->where('keterangan', 'Penyesuaian Gaji Pokok')
                    ->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $i)
                    ->first();
            if($i == 1){
                $penyesuaian_cari = DB::table('history_penyesuaian_gaji')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', null)
                    ->where('keterangan', 'Penyesuaian Gaji Pokok')
                    ->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $i)
                    ->first();
                $z = $i;
            }
            elseif($i>1){
                if ($penyesuaian == []) {
                    $penyesuaian_cari = DB::table('history_penyesuaian_gaji')
                        ->where('nip', $nip)
                        ->where('id_tunjangan', null)
                        ->where('keterangan', 'Penyesuaian Gaji Pokok')
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $z)
                        ->first();
                } else {
                    $penyesuaian_cari = DB::table('history_penyesuaian_gaji')
                        ->where('nip', $nip)
                        ->where('id_tunjangan', null)
                        ->where('keterangan', 'Penyesuaian Gaji Pokok')
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $i)
                        ->first();
                    $z = $i;
                }
                
            }
            $pokok = DB::table('mst_karyawan')
                ->where('nip', $nip)
                ->first();
            $gj_pokok[$i-1] = ($tes != null) ? $tes->nominal_baru : $pokok->gj_pokok;
            $gj_penyesuaian[$i-1] = ($penyesuaian_cari != null) ? $penyesuaian_cari->nominal_baru : $pokok->gj_penyesuaian;

            // Get tunjangan karyawan
            for($j = 1; $j <= 14; $j++){
                if($i != 10 || $i != 9 || $i != 4){
                    $tnj = DB::table('history_penyesuaian_gaji')
                        ->where('nip', $nip)
                        ->where('id_tunjangan', $j)
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $i)
                        ->first();
                    if($i == 1){
                        $tnj = DB::table('history_penyesuaian_gaji')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', $j)
                            ->whereYear('created_at', $tahun)
                            ->whereMonth('created_at', $i)
                            ->first();
                        $y = $i;
                    }
                    elseif($i>1){
                        if ($pokok_penyesuaian == []) {
                            $tnj = DB::table('history_penyesuaian_gaji')
                                ->where('nip', $nip)
                                ->where('id_tunjangan', $j)
                                ->whereYear('created_at', $tahun)
                                ->whereMonth('created_at', $y)
                                ->first();
                        } else {
                            $tnj = DB::table('history_penyesuaian_gaji')
                                ->where('nip', $nip)
                                ->where('id_tunjangan', $j)
                                ->whereYear('created_at', $tahun)
                                ->whereMonth('created_at', $i)
                                ->first();
                            $y = $i;
                        }
                    }
                    $tun = DB::table('tunjangan_karyawan')
                        ->where('nip', $nip)
                        ->where('id_tunjangan', $j)
                        ->first();
                    if($tnj != null){
                        $tj[$j-1] = $tnj->nominal_baru;
                    } else if($tnj == null && $tun != null){
                        $tj[$j-1] = $tun->nominal;
                    } else {
                        $tj[$j-1] = 0;
                    }
                }
            }
            array_push($tk, $tj);
            
            // Get Penghasilan tidak teratur karyawan
            for($j = 16; $j <= 21; $j++){
                $penghasilan = DB::table('penghasilan_tidak_teratur')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', $j)
                    ->where('tahun', $tahun)
                    ->where('bulan', $i)
                    ->first();
                $peng[$j-16] = ($penghasilan != null) ? $penghasilan->nominal : 0;
            }
            array_push($ptt, $peng);

            // Get Bonus Karyawan
            for($j = 21; $j <= 24; $j++){
                $bns = DB::table('penghasilan_tidak_teratur')
                    ->where('nip', $nip)
                    ->where('id_tunjangan', $j)
                    ->where('tahun', $tahun)
                    ->where('bulan', $i)
                    ->first();
                $bon[$j-21] = ($bns != null) ? $bns->nominal : 0;
            }
            array_push($bonus, $bon);

            // Get Total Tunjangan Karyawan
            if($i == 1){
                $total_tunjangan = DB::table('history_penyesuaian_gaji')
                    ->join('mst_tunjangan', 'history_penyesuaian_gaji.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->where('nip', $nip)
                    ->where('mst_tunjangan.status', 1)
                    ->whereYear('history_penyesuaian_gaji.created_at', '=', $tahun)
                    ->whereMonth('history_penyesuaian_gaji.created_at', '=', $i)
                    ->sum('history_penyesuaian_gaji.nominal_baru');
                $w = $i;
            }
            elseif($i>1){
                if ($pokok_penyesuaian == []) {
                    $total_tunjangan = DB::table('history_penyesuaian_gaji')
                        ->join('mst_tunjangan', 'history_penyesuaian_gaji.id_tunjangan', '=', 'mst_tunjangan.id')
                        ->where('nip', $nip)
                        ->where('mst_tunjangan.status', 1)
                        ->whereYear('history_penyesuaian_gaji.created_at', '=', $tahun)
                        ->whereMonth('history_penyesuaian_gaji.created_at', '=', $w)
                        ->sum('history_penyesuaian_gaji.nominal_baru');
                } else {
                    $total_tunjangan = DB::table('history_penyesuaian_gaji')
                        ->join('mst_tunjangan', 'history_penyesuaian_gaji.id_tunjangan', '=', 'mst_tunjangan.id')
                        ->where('nip', $nip)
                        ->where('mst_tunjangan.status', 1)
                        ->whereYear('history_penyesuaian_gaji.created_at', '=', $tahun)
                        ->whereMonth('history_penyesuaian_gaji.created_at', '=', $i)
                        ->sum('history_penyesuaian_gaji.nominal_baru');
                    $w = $i;
                }
            }
            $tunjangan = DB::table('tunjangan_karyawan')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->where('tunjangan_karyawan.nip', $nip)
                ->sum('nominal');
            array_push($total_tj, ($total_tunjangan != null) ? $total_tunjangan : $tunjangan);
            
            $total_gaji = $gj_pokok[$i-1] + $gj_penyesuaian[$i-1] + $total_tj[$i-1];
            $jms = 0.03 * $total_gaji;
            array_push($jamsostek, $jms);
        }
        // dd($ptt);

        return view('penghasilan.gajipajak', [
            'gj_pokok' => $gj_pokok,
            'jamsostek' => $jamsostek,
            'tunjangan' => $tk,
            'penghasilan' => $ptt,
            'bonus' => $bonus,
            'penyesuaian' => $gj_penyesuaian
        ]);
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
        $tj = DB::table('mst_tunjangan')
            ->whereIn('id', [11, 12, 13, 14])
            ->get();
        $tidak_teratur = DB::table('mst_tunjangan')
            ->whereIn('id', [16, 17, 18, 19, 20, 21])
            ->get();
        $bonus = DB::table('mst_tunjangan')
            ->whereIn('id', [22, 23, 24])
            ->get();
        
        return view('penghasilan.add', [
            'tj' => $tj,
            'tidak_teratur' => $tidak_teratur,
            'bonus' => $bonus
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
            return redirect()->route('penghasilan-tidak-teratur.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('penghasilan-tidak-teratur.index');
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->route('penghasilan-tidak-teratur.index');
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
