<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JaminanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function getJamsostek()
     {
        return view('jaminan.jamsostek', [
            'status' => null
        ]);
     }

     public function postJamsostek(Request $request)
     {
        $kantor = $request->kantor;
        $tipe = $request->tipe;
        $karyawan = DB::table('mst_karyawan')
            ->get();

        if($request->kategori == 1){
            $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
            $cbg = array();
            foreach($cabang as $item){
                array_push($cbg, $item->kd_cabang);
            }

            $data_pusat = DB::table('tunjangan_karyawan')
                ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->whereNotIn('kd_entitas', $cbg)
                ->sum('nominal');

            $data_cabang = DB::table('tunjangan_karyawan')
                ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->whereIn('kd_entitas', $cbg)
                ->selectRaw('kd_entitas, sum(nominal) as nominal')
                ->groupBy('mst_karyawan.kd_entitas')
                ->get();
                // dd($data_cabang);
            return view('jaminan.jamsostek', [
                'status' => 1,
                'data_pusat' => $data_pusat,
                'data_cabang' => $data_cabang
            ]);
        }

        if($kantor == 'Pusat'){
            $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
            $cbg = array();
            foreach($cabang as $item){
                array_push($cbg, $item->kd_cabang);
            }
            // dd($cbg);
            $karyawan = DB::table('mst_karyawan')
                ->whereNotIn('kd_entitas', $cbg)
                ->orWhere('kd_entitas', null)
                ->get();

            $total_gaji = array();
            foreach($karyawan as $i){
                $data_gaji = DB::table('tunjangan_karyawan')
                    ->join('mst_tunjangan', 'tunjangan_karyawan.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->where('nip', $i->nip)
                    ->where('mst_tunjangan.status', 1)
                    ->sum('tunjangan_karyawan.nominal');
                // dd($i->nama_karyawan. ' '.$data_gaji.' '.  $i->gj_pokok);
                array_push($total_gaji, ($data_gaji + $i->gj_pokok));
            }
        } else {
            $cabang = $request->get('cabang');
            $karyawan = DB::table('mst_karyawan')
                ->where('kd_entitas', $cabang)
                ->get();

            $total_gaji = array();
            foreach($karyawan as $i){
                $data_gaji = DB::table('tunjangan_karyawan')
                    ->join('mst_tunjangan', 'tunjangan_karyawan.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->where('nip', $i->nip)
                    ->where('mst_tunjangan.status', 1)
                    ->sum('tunjangan_karyawan.nominal');
                // dd($i->nama_karyawan. ' '.$data_gaji.' '.  $i->gj_pokok);
                array_push($total_gaji, ($data_gaji + $i->gj_pokok));
            }
        }
        // dd($karyawan);
        $jp1 = array();
        $jp2 = array();

        foreach($total_gaji as $item){
            $perhitungan_jp1 = ($item >  9077600) ?  9077600 * 0.001 : $item * 0.001;
            $perhitungan_jp2 = ($item >  9077600) ?  9077600 * 0.002 : $item * 0.002;
            array_push($jp1, $perhitungan_jp1);
            array_push($jp2, $perhitungan_jp2);
        }

        return view('jaminan.jamsostek', [
            'status' => 2,
            'karyawan' => $karyawan,
            'jp1' => $jp1,
            'jp2' => $jp2
        ]);
     }

    public function index()
    {
        return view('jaminan.index', [
            'karyawan' => null,
            'status' => null
        ]);
    }

    public function filter(Request $request)
    {
        $kantor = $request->kantor;
        $tipe = $request->tipe;
        $karyawan = DB::table('mst_karyawan')
            ->get();

        if($request->kategori == 1){
            $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
            $cbg = array();
            foreach($cabang as $item){
                array_push($cbg, $item->kd_cabang);
            }

            $data_pusat = DB::table('tunjangan_karyawan')
                ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->whereNotIn('kd_entitas', $cbg)
                ->sum('nominal');

            $data_cabang = DB::table('tunjangan_karyawan')
                ->join('mst_karyawan', 'mst_karyawan.nip', '=', 'tunjangan_karyawan.nip')
                ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                ->where('mst_tunjangan.status', 1)
                ->whereIn('kd_entitas', $cbg)
                ->selectRaw('kd_entitas, sum(nominal) as nominal')
                ->groupBy('mst_karyawan.kd_entitas')
                ->get();
                // dd($data_cabang);
            return view('jaminan.index', [
                'status' => 1,
                'data_pusat' => $data_pusat,
                'data_cabang' => $data_cabang
            ]);
        }

        if($kantor == 'Pusat'){
            $cabang = DB::table('mst_cabang')->select('kd_cabang')->get();
            $cbg = array();
            foreach($cabang as $item){
                array_push($cbg, $item->kd_cabang);
            }
            // dd($cbg);
            $karyawan = DB::table('mst_karyawan')
                ->whereNotIn('kd_entitas', $cbg)
                ->orWhere('kd_entitas', null)
                ->get();

            $total_gaji = array();
            foreach($karyawan as $i){
                $data_gaji = DB::table('tunjangan_karyawan')
                    ->join('mst_tunjangan', 'tunjangan_karyawan.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->where('nip', $i->nip)
                    ->where('mst_tunjangan.status', 1)
                    ->sum('tunjangan_karyawan.nominal');
                // dd($i->nama_karyawan. ' '.$data_gaji.' '.  $i->gj_pokok);
                array_push($total_gaji, ($data_gaji + $i->gj_pokok));
            }
        } else {
            $cabang = $request->get('cabang');
            $karyawan = DB::table('mst_karyawan')
                ->where('kd_entitas', $cabang)
                ->get();

            $total_gaji = array();
            foreach($karyawan as $i){
                $data_gaji = DB::table('tunjangan_karyawan')
                    ->join('mst_tunjangan', 'tunjangan_karyawan.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->where('nip', $i->nip)
                    ->where('mst_tunjangan.status', 1)
                    ->sum('tunjangan_karyawan.nominal');
                // dd($i->nama_karyawan. ' '.$data_gaji.' '.  $i->gj_pokok);
                array_push($total_gaji, ($data_gaji + $i->gj_pokok));
            }
        }
        // dd($karyawan);
        $jkk = array();
        $jht = array();
        $jkm = array();

        foreach($total_gaji as $item){
            $perhitungan_jkk = 0.0024 * $item;
            $perhitungan_jht = 0.057 * $item;
            $perhitungan_jkm = 0.003 * $item;
            array_push($jkk, $perhitungan_jkk);
            array_push($jht, $perhitungan_jht);
            array_push($jkm, $perhitungan_jkm);
        }

        return view('jaminan.index', [
            'status' => 2,
            'karyawan' => $karyawan,
            'jkk' => $jkk,
            'jht' => $jht,
            'jkm' => $jkm
        ]);
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
