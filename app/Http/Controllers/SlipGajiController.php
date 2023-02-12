<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlipGajiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('slip_gaji.laporan_gaji', ['data' => null, 'kategori' => null]);
    }

    function getLaporanGaji($karyawan, $kategori)
    {
        foreach($karyawan as $i => $item){
            // Get Status Karyawan Untuk PTKP
            if($item->status != 'K' || $item->status != 'TK'){
                $status = 'TK';
            } else{
                $status = $item->status;
                if($status == 'K'){
                    $jmlAnak = DB::table('keluarga')
                        ->where('nip')
                        ->whereIn('enum', ['Suami', 'Istri'])
                        ->first();
                    $jmlAnak = ($jmlAnak != null) ? $jmlAnak->jml_anak : '0';
                    if($jmlAnak > 3){
                        $jmlAnak = 3;
                    }
                    $status = $status.'/'.$jmlAnak;
                }
            }
            $ptkp = DB::table('set_ptkp')
                ->where('kode', $status)
                ->first();

            $data[$i]['nama'] = $item->nama_karyawan;
            $data[$i]['gj_pokok'] = $item->gj_pokok;
            $data[$i]['gj_penyesuaian'] = $item->gj_penyesuaian;
            $totalGaji = $item->gj_pokok + $item->gj_penyesuaian;
            for($j = 1; $j <=8; $j++){
                if($j != 4){
                    $tunjangan = DB::table('tunjangan_karyawan')
                        ->where('nip', $item->nip)
                        ->where('id_tunjangan', $j)
                        ->first('nominal');
                    $data[$i]['tunjangan'][$j] = ($tunjangan != null) ? $tunjangan->nominal : 0;
                    $totalGaji += ($tunjangan != null) ? $tunjangan->nominal : 0;
                }
            }
            $data[$i]['total'] = $totalGaji;

            if($kategori == 2){
                $data[$i]['norek'] = $item->no_rekening ?? '-';
                $data[$i]['potongan'][0] = (($totalGaji >  9077600) ?  round(9077600 * 0.01) : round($totalGaji * 0.01));
                $dpp = DB::table('tunjangan_karyawan')
                    ->where('id_tunjangan', 15)
                    ->where('nip', $item->nip)
                    ->first();
                $data[$i]['potongan'][1] = $dpp->nominal ?? 0; 
                $data[$i]['potongan'][2] = 0;
                $data[$i]['potongan'][3] = 0;
                $data[$i]['potongan'][4] = 0;
                $data[$i]['potongan'][5] = 0;
                
            }
        }
        
        return $data;
    }

    public function getLaporan(Request $request)
    {
        $kantor = $request->kantor;
        $kategori = $request->kategori;
        $data = [];
        if($kantor == 'cabang'){
            $karyawan = DB::table('mst_karyawan')
                ->where('kd_entitas', $request->cabang)
                ->get();
        } else if($kantor == 'pusat'){
            $cabang = DB::table('mst_cabang')
                ->select('kd_cabang')
                ->get();
            $cbg = [];
            foreach($cabang as $i){
                array_push($cbg, $i->kd_cabang);
            }
            $karyawan = DB::table('mst_karyawan')
                ->whereNotIn('kd_entitas', $cbg)
                ->orWhere('kd_entitas', null)
                ->get();
        }
        $data = $this->getLaporanGaji($karyawan, $kategori);
        return view('slip_gaji.laporan_gaji', compact('data', 'kategori'));
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
        //
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
