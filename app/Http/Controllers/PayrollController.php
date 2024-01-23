<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Repository\CabangRepository;
use App\Repository\PayrollRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // return $total;
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

        // get karyawan
        $ttdKaryawan = KaryawanModel::select(
                    'mst_karyawan.nip',
                    'mst_karyawan.nik',
                    'mst_karyawan.nama_karyawan',
                    'mst_karyawan.kd_bagian',
                    'mst_karyawan.kd_jabatan',
                    'mst_karyawan.kd_entitas',
                    'mst_karyawan.tanggal_penonaktifan',
                    'mst_karyawan.status_jabatan',
                    'mst_karyawan.ket_jabatan',
                    'mst_karyawan.kd_entitas',
                    DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor")
                )
                ->with('jabatan')
                ->with('bagian')
                ->whereIn('kd_jabatan',['PIMDIV','PSD'])
                ->whereIn('kd_entitas',['UMUM','SDM'])
                ->whereNull('tanggal_penonaktifan')
                ->get();
        foreach ($ttdKaryawan as $key => $krywn) {
            $krywn->prefix = match($krywn->status_jabatan) {
                'Penjabat' => 'Pj. ',
                'Penjabat Sementara' => 'Pjs. ',
                default => '',
            };

            $jabatan = $krywn->jabatan->nama_jabatan;

            $krywn->ket = $krywn->ket_jabatan ? "({$krywn->ket_jabatan})" : "";

            if(isset($krywn->entitas->subDiv)) {
                $krywn->entitas_result = $krywn->entitas->subDiv->nama_subdivisi;
            } else if(isset($krywn->entitas->div)) {
                $krywn->entitas_result = $krywn->entitas->div->nama_divisi;
            } else {
                $krywn->entitas_result = '';
            }

            if ($jabatan == "Pemimpin Sub Divisi") {
                $jabatan = 'PSD';
            } else if ($jabatan == "Pemimpin Bidang Operasional") {
                $jabatan = 'PBO';
            } else if ($jabatan == "Pemimpin Bidang Pemasaran") {
                $jabatan = 'PBP';
            } else {
                $jabatan = $krywn->jabatan->nama_jabatan;
            }

            $krywn->jabatan_result = $jabatan;
        }
        $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';
        if (auth()->user()->hasRole('cabang')) {
            $pincab = DB::table('mst_karyawan')->where('kd_jabatan', 'PC')->where('kd_entitas', $kd_entitas)->first();
            $cabang = DB::table('mst_cabang')->select('kd_cabang', 'nama_cabang')->where('kd_cabang', $kd_entitas)->first();
        } else {
            $pincab = null;
            $cabang = null;
        }
        if ($kategori == 'payroll'){
            return view('payroll.tables.payroll-pdf', ['data' => $data, 'pincab' => $pincab, 'cabang' => $cabang, 'ttdKaryawan' => $ttdKaryawan]);

        }elseif ($kategori = 'rincian') {
            return view('payroll.tables.rincian-pdf', ['data' => $data,'pincab' => $pincab, 'cabang' => $cabang, 'ttdKaryawan' => $ttdKaryawan]);
        }else{
            Alert::error('Terjadi kesalahan');
            return redirect()->route('payroll.index');
        }

    }
}
