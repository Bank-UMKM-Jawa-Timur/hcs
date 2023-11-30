<?php

namespace App\Http\Controllers;

use App\Repository\PayrollRepository;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index() {
        $data = $this->listSlipGaji();
        return $data;
        // return count($data);
        return view('payroll.index', compact('data'));
    }

    public function listSlipGaji() {
        $payrollRepository = new PayrollRepository;
        $kantor = 'Pusat';
        $month = 8;
        $year = '2023';
        $q = '';
        $data = $payrollRepository->get($kantor, $month, $year, $q);

        return $data;
    }
}
