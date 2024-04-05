<?php

namespace App\Http\Controllers\LaporanPergerakanKarir;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Service\EntityService;
use Illuminate\Http\Request;
use App\Repository\KaryawanRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanPenonaktifanController extends Controller
{
    public function index(Request $request) {
        if (!auth()->user()->can('laporan - laporan pergerakan karir - laporan penonaktifan')) {
            return view('roles.forbidden');
        }
        $data = null;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;

        $karyawanRepo = new KaryawanRepository();

        if($start_date && $end_date){
            $data = $karyawanRepo->filterKaryawanPusatNonaktif($start_date, $end_date, $limit, $page, $search);

            // Record to log activity
            $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
            $activity = "Pengguna <b>$name</b> mengakses laporan penonaktifan untuk rentang waktu <b>$start_date</b> sampai dengan <b>$end_date</b>";
            LogActivity::create($activity);    
            
        }
        return view('laporan_pergerakan_karir.penonaktifan.index', compact('data'));
    }
}
