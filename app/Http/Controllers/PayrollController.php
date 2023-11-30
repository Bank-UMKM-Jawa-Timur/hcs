<?php

namespace App\Http\Controllers;

use App\Repository\CabangRepository;
use App\Repository\PayrollRepository;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request) {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $search = $request->get('q');
        $kantor = 'Pusat';
        $month = 8;
        $year = '2023';
        $q = '';
        
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

    public function templateCetak (){
        return view('payroll.template');
    }
}
