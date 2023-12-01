<?php

namespace App\Http\Controllers;

use App\Repository\CabangRepository;
use App\Repository\PayrollRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request) {
        $this->validate($request, [
            'kantor' => 'not_in:0',
            'bulan' => 'not_in:0',
            'tahun' => 'not_in:0'
        ]);

        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $search = $request->get('q');
        $kantor = $request->get('kantor') == 'pusat' ? 'pusat' : $request->get('cabang');
        $month = $request->get('bulan');
        $year = $request->get('tahun');
        
        $cabangRepo = new CabangRepository;
        $cabang = $cabangRepo->listCabang();

        $data = $this->listSlipGaji($kantor, $month, $year, $search, $page, $limit);

        return view('payroll.index', compact('data', 'cabang'));
    }

    public function listSlipGaji($kantor, $month, $year, $q, $page, $limit) {
        $payrollRepository = new PayrollRepository;
        $data = $payrollRepository->get($kantor, $month, $year, $q, $page, $limit);

        return $data;
    }

    public function cetakSlip(){
        return view('payroll.print.slip');
    }
}
