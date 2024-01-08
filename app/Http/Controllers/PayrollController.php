<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Repository\CabangRepository;
use App\Repository\PayrollRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;
use RealRashid\SweetAlert\Facades\Alert;

class PayrollController extends Controller
{
    public $param;

    public function index(Request $request) {
        // Need permission
        if (!auth()->user()->can('penghasilan - payroll - list payroll')) {
            return view('roles.forbidden');
        }
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
        if ($request->get('kantor') == 'cabang') {
            if ($request->get('cabang') == 0) {
                Alert::warning('Peringatan', 'Harap pilih cabang terlebih dahulu.');
                return back();
            }
        }
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
        $total = $this->grandTotal($kantor, $month, $year, $search, $page, $limit,null);
        return view('payroll.index', compact('data', 'cabang','total'));
    }

    function grandTotal($kantor, $month, $year, $q, $page, $limit, $cetak) {
        $payrollRepository = new PayrollRepository;
        $data = $payrollRepository->total($kantor, $month, $year, $q, $page, $limit,$cetak);

        return $data;
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
        $data = $this->list($kantor, $month, $year, $search, $page, null,'cetak');
        if ($kategori == 'payroll'){
            return view('payroll.tables.payroll-pdf', ['data' => $data]);

        }elseif ($kategori = 'rincian') {
            return view('payroll.tables.rincian-pdf', ['data' => $data]);
        }else{
            Alert::error('Terjadi kesalahan');
            return redirect()->route('payroll.index');
        }

    }
}
