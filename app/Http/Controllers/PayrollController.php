<?php

namespace App\Http\Controllers;

use App\Repository\CabangRepository;
use App\Repository\PayrollRepository;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session as FacadesSession;
use RealRashid\SweetAlert\Facades\Alert;

class PayrollController extends Controller
{
    public $param;

    public function index(Request $request) {
        FacadesSession::forget('kategori');
        FacadesSession::forget('kantor');
        FacadesSession::forget('month');
        FacadesSession::forget('year');

        $this->validate($request, [
            'kantor' => 'not_in:0|required_with:cabang',
            'kategori' => 'not_in:0',
            'bulan' => 'not_in:0',
            'tahun' => 'not_in:0'
        ]);
        FacadesSession::put('kategori',$request->get('kategori'));

        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $search = $request->get('q');

        $kantor = $request->get('kantor') == 'pusat' ? 'pusat' : $request->get('cabang');
        FacadesSession::put('kantor',$kantor);

        $month = $request->get('bulan');
        FacadesSession::put('month',$month);

        $year = $request->get('tahun');
        FacadesSession::put('year',$year);

        $cabangRepo = new CabangRepository;
        $cabang = $cabangRepo->listCabang();

        $this->param = null;

        $data = $this->list($kantor, $month, $year, $search, $page, $limit,null);

        return view('payroll.index', compact('data', 'cabang'));
    }

    public function list($kantor, $month, $year, $q, $page, $limit, $cetak) {
        $payrollRepository = new PayrollRepository;
        $data = $payrollRepository->get($kantor, $month, $year, $q, $page, $limit,$cetak);

        return $data;
    }

    function cetak() {
        $kantor = FacadesSession::get('kantor');
        $month = FacadesSession::get('month');
        $year = FacadesSession::get('year');
        $kategori = FacadesSession::get('kategori');
        $search = null;
        $page = null;
        $data = $this->listSlipGaji($kantor, $month, $year, $search, $page, 10, 'cetak');
        if ($kategori == 'payroll'){
            return view('payroll.tables.payroll-pdf', ['data' => $data]);

        }elseif ($kategori = 'rincian') {
            return view('payroll.tables.rincian-pdf', ['data' => $data]);
        }else{
            Alert::error('Terjadi kesalahan');
            return redirect()->route('payroll.index');
        }

    }

    public function cetakSlip(){
        return view('payroll.print.slip');
    }

    public function slip(Request $request) {
        $this->validate($request, [
            'kantor' => 'not_in:0',
            'bulan' => 'not_in:0',
            'tahun' => 'not_in:0'
        ]);

        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $search = $request->get('q');
        $kantor = $request->get('kantor') == 'pusat' ? 'pusat' : $request->get('cabang');
        $kd_entitas = $request->get('cabang');
        $divisi = $request->get('divisi');
        $sub_divisi = $request->get('sub_divisi');
        $bagian = $request->get('bagian');
        $nip = $request->get('nip');
        $month = $request->get('bulan');
        $year = $request->get('tahun');

        // Retrieve cabang data
        $cabangRepo = new CabangRepository;
        $cabang = $cabangRepo->listCabang();

        $data = $this->listSlipGaji($kantor, $divisi, $sub_divisi, $bagian, $nip, $month, $year, $search, $page, $limit,null);

        return view('payroll.slip', compact('data', 'cabang'));
    }

    public function listSlipGaji($kantor, $divisi, $sub_divisi, $bagian, $nip, $month, $year, $q, $page, $limit, $cetak) {
        $payrollRepository = new PayrollRepository;
        $data = $payrollRepository->getSlip($kantor, $divisi, $sub_divisi, $bagian, $nip, $month, $year, $q, $page, $limit,$cetak);

        return $data;
    }
}
