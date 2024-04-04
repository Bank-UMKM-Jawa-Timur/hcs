<?php

namespace App\Http\Controllers;

use App\Helpers\HitungPPH;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KalkulatorPPHController extends Controller
{
    public function index(Request $request) : View
    {
        $result = null;
        $kode_entitas = DB::table('mst_cabang')
                            ->select('kd_cabang')
                            ->orderBy('kd_cabang')
                            ->get();

        $ptkp = DB::table('set_ptkp')
                    ->select('kode')
                    ->orderBy('id')
                    ->get();

        if ($request->has('bruto') && $request->has('ptkp') &&
            $request->has('bulan') && $request->has('tahun')) {
            $bulan = $request->get('bulan');
            $tahun = $request->get('tahun');
            $selected_ptkp = $request->get('ptkp');
            $bruto = $request->get('bruto');

            $result = HitungPPH::calcPPH58($bulan, $tahun, $selected_ptkp, $bruto);
        }

        return view('kalkulator-pph.index', compact('ptkp', 'kode_entitas', 'result'));
    }
}
