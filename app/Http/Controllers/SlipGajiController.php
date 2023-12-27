<?php

namespace App\Http\Controllers;

use App\Imports\ImportPotonganGaji;
use App\Models\KaryawanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session as FacadesSession;
use App\Repository\CabangRepository;
use App\Repository\SlipGajiRepository;
use Barryvdh\DomPDF\Facade\Pdf;

class SlipGajiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->hasRole(['kepegawaian','admin'])) {
            return view('roles.forbidden');
        }
        return view('slip_gaji.laporan_gaji', ['data' => null, 'kategori' => null, 'request' => null]);
    }

    public function slipJurnalIndex()
    {
        if (!auth()->user()->hasRole(['kepegawaian','admin'])) {
            return view('roles.forbidden');
        }
        return view('slip_gaji.slip_jurnal', ['data' => null, 'kategori' => null, 'request' => null]);
    }

    function getSlipJurnal($request, $kategori, $karyawan)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $value = [];

        $cek_data = DB::table('gaji_per_bulan')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->count('*');

        if($kategori == 1){
            $value['item'] = ['Biaya Pegawai', 'Pph 21 Pegawai', 'Tabungan Sikemas', 'Iuran Koperasi Pegawai', 'Iuran IK', 'Dana Pensiun Pegawai ( 5% DPP )', 'Titipan Lainnya ( 1% JP BPJS TK )', 'Angsuran Kredit Pegawai', 'Angsuran kredit koperasi'];
            $value['kode_rekening'] = ['52103', '20303', '20102', '0013011797', '0013011794', '0013021911', '21101', '0019000003', '0013011797'];
            $value[0] = 0;
            $value[1] = 0;
            $value[2] = 0;
            $value[3] = 0;
            $value[4] = 0;
            $value[5] = 0;
            $value[6] = 0;
            $value[7] = 0;
            $value[8] = 0;
            $totalGaji = 0;
            foreach($karyawan as $i => $item){
                if($cek_data > 0){
                    $gaji = DB::table('gaji_per_bulan')
                        ->where('nip', $item->nip)
                        ->where('tahun', $tahun)
                        ->where('bulan', $bulan)
                        ->first();

                    if ($gaji == null) {
                        // $data[$i]['tunjangan'][1] = $gaji->tj_keluarga;
                        // $data[$i]['tunjangan'][2] = $gaji->tj_telepon;
                        // $data[$i]['tunjangan'][3] = $gaji->tj_teller;
                        // $data[$i]['tunjangan'][4] = $gaji->tj_jabatan;
                        // $data[$i]['tunjangan'][5] = $gaji->tj_perumahan;
                        // $data[$i]['tunjangan'][6] = $gaji->tj_pelaksana;
                        // $data[$i]['tunjangan'][7] = $gaji->tj_kemahalan;
                        // $data[$i]['tunjangan'][8] = $gaji->tj_kesejahteraan;
                        $totalGaji = 0 + 0 + 0;
                    }else{
                        $data[$i]['tunjangan'][1] = $gaji->tj_keluarga;
                        $data[$i]['tunjangan'][2] = $gaji->tj_telepon;
                        $data[$i]['tunjangan'][3] = $gaji->tj_teller;
                        $data[$i]['tunjangan'][4] = $gaji->tj_jabatan;
                        $data[$i]['tunjangan'][5] = $gaji->tj_perumahan;
                        $data[$i]['tunjangan'][6] = $gaji->tj_pelaksana;
                        $data[$i]['tunjangan'][7] = $gaji->tj_kemahalan;
                        $data[$i]['tunjangan'][8] = $gaji->tj_kesejahteraan;
                        $totalGaji = $gaji->gj_pokok + $gaji->gj_penyesuaian + array_sum($data[$i]['tunjangan']);
                    }

                }
                else{
                    $totalGaji = $item->gj_pokok + $item->gj_penyesuaian;
                    for($j = 1; $j <=9; $j++){
                        if($j != 4){
                            $tunjangan = DB::table('tunjangan_karyawan')
                                ->where('nip', $item->nip)
                                ->where('id_tunjangan', $j)
                                ->first('nominal');
                            $data[$i]['tunjangan'][$j] = ($tunjangan != null) ? $tunjangan->nominal : 0;
                            $totalGaji = ($tunjangan != null) ? $tunjangan->nominal : 0;
                        }
                    }
                }
                $jk = (($totalGaji >  9077600) ?  round(9077600 * 0.01) : round($totalGaji * 0.01));
                $data[$i]['potongan'][0] = $jk;
                $dpp = DB::table('tunjangan_karyawan')
                    ->where('id_tunjangan', 15)
                    ->where('nip', $item->nip)
                    ->first();
                $pengurang = DB::table('potongan_gaji')
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('nip', $item->nip)
                    ->first();
                $data[$i]['potongan'][1] = $dpp->nominal ?? 0;
                $data[$i]['potongan'][2] = $pengurang->kredit_koperasi ?? 0;
                $data[$i]['potongan'][3] = $pengurang->iuran_koperasi ?? 0;
                $data[$i]['potongan'][4] = $pengurang->kredit_pegawai ?? 0;
                $data[$i]['potongan'][5] = $pengurang->iuran_ik ?? 0;
    
                $value[0] += $totalGaji + array_sum($data[$i]['potongan']);
                $value[1] += 0;
                $value[2] += $totalGaji;
                $value[3] += $pengurang->iuran_koperasi ?? 0;
                $value[4] += $pengurang->iuran_ik ?? 0;
                $value[5] += $dpp->nominal ?? 0;
                $value[6] += $jk;
                $value[7] += $pengurang->kredit_pegawai ?? 0;
                $value[8] += $pengurang->kredit_koperasi ?? 0;
            }
        } else if($kategori == 2){
            $value['tj_vitamin'] = 0;
            foreach($karyawan as $i => $item){
                if($cek_data > 0){
                    $tunjangan = DB::table('gaji_per_bulan')
                        ->where('bulan', $bulan)
                        ->where('tahun', $tahun)
                        ->where('nip', $item->nip)
                        ->first('tj_vitamin');
                    $tj_vitamin = $tunjangan->tj_vitamin ?? 0;
                } else {
                    $tunjangan = DB::table('tunjangan_karyawan')
                        ->where('nip', $item->nip)
                        ->where('id_tunjangan', 13)
                        ->first('nominal');
                    $tj_vitamin = $tunjangan->nominal ?? 0;
                }

                $value['tj_vitamin'] += $tj_vitamin;
            }
        } else if($kategori == 3){
            $value['thr'] = 0;
            foreach($karyawan as $i => $item){
                $bonus = DB::table('tunjangan_karyawan')
                    ->where('id_tunjangan', 22)
                    ->where('nip', $item->nip)
                    ->first();
                $thr = $bonus->nominal ?? 0;
                $value['thr'] += $thr;
            }
        }
        return $value;
    }

    public function SlipJurnal(Request $request)
    {
        $kantor = $request->kantor;
        $kategori = $request->kategori;

        // if($kantor == 'pusat'){
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
        // } else{
        //     $karyawan = DB::table('mst_karyawan')
        //         ->where('kd_entitas', $request->cabang)
        //         ->get();
        // }

        $data = $this->getSlipJurnal($request, $kategori, $karyawan);
        // dd($data);
        return view('slip_gaji.slip_jurnal', compact('data', 'kategori', 'request'));
    }

    function getLaporanGaji($karyawan, $kategori, $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $cek_data = DB::table('gaji_per_bulan')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->count('*');
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

            if($cek_data > 0){
               $gaji = DB::table('gaji_per_bulan')
                    ->where('nip', $item->nip)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->first();

                $data[$i]['gj_pokok'] = $gaji->gj_pokok;
                $data[$i]['gj_penyesuaian'] = $gaji->gj_penyesuaian;

                $data[$i]['tunjangan'][0] = $gaji->tj_keluarga;
                $data[$i]['tunjangan'][1] = $gaji->tj_teller;
                $data[$i]['tunjangan'][2] = $gaji->tj_telepon;
                $data[$i]['tunjangan'][3] = $gaji->tj_jabatan;
                $data[$i]['tunjangan'][4] = $gaji->tj_perumahan;
                $data[$i]['tunjangan'][5] = $gaji->tj_pelaksana;
                $data[$i]['tunjangan'][6] = $gaji->tj_kemahalan;
                $data[$i]['tunjangan'][7] = $gaji->tj_kesejahteraan;

                $totalGaji = $gaji->gj_pokok + $gaji->gj_penyesuaian + array_sum($data[$i]['tunjangan']);
            }
            else{
                $data[$i]['gj_pokok'] = $item->gj_pokok;
                $data[$i]['gj_penyesuaian'] = $item->gj_penyesuaian;
                $totalGaji = $item->gj_pokok + $item->gj_penyesuaian;
                for($j = 1; $j <=9; $j++){
                    if($j != 4){
                        $tunjangan = DB::table('tunjangan_karyawan')
                            ->where('nip', $item->nip)
                            ->where('id_tunjangan', $j)
                            ->first('nominal');
                        $data[$i]['tunjangan'][$j] = ($tunjangan != null) ? $tunjangan->nominal : 0;
                        $totalGaji += ($tunjangan != null) ? $tunjangan->nominal : 0;
                    }
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
                $pengurang = DB::table('potongan_gaji')
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('nip', $item->nip)
                    ->first();
                $data[$i]['potongan'][1] = $dpp->nominal ?? 0;
                $data[$i]['potongan'][2] = $pengurang->kredit_koperasi ?? 0;
                $data[$i]['potongan'][3] = $pengurang->iuran_koperasi ?? 0;
                $data[$i]['potongan'][4] = $pengurang->kredit_pegawai ?? 0;
                $data[$i]['potongan'][5] = $pengurang->iuran_ik ?? 0;
            }
        }

        return $data;
    }

    public function getLaporan(Request $request)
    {
        $kantor = $request->kantor;
        $Opsicabang = $request->cabang;
        $kategori = $request->kategori;
        $data = [];

        if($kantor == 'cabang'){
            $karyawan = DB::table('mst_karyawan')
                ->where('kd_entitas', $Opsicabang)
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
                // Comment by arsyad entitas terdapat pilihan untuk memilih cabang tertentu di kategori
                ->get();
        }
        $data = $this->getLaporanGaji($karyawan, $kategori, $request);
        return view('slip_gaji.laporan_gaji', compact('data', 'kategori', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('slip_gaji.import');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('upload_csv');
        $import = new ImportPotonganGaji;
        $import = $import->import($file);

        Alert::success('Berhasil', 'Berhasil mengimport data excel');
        return redirect()->route('slip_gaji.index');
    }

    public function slip(Request $request) {
        // Need permission
        if (!auth()->user()->hasRole(['kepegawaian','admin'])) {
            return view('roles.forbidden');
        }
        FacadesSession::forget('year');
        FacadesSession::forget('nip');
        $this->validate($request, [
            'nip' => 'not_in:0',
            'tahun' => 'not_in:0'
        ]);

        $nip = $request->get('nip');
        $year = $request->get('tahun');

        FacadesSession::put('nip',$nip);
        FacadesSession::put('year',$year);

        // Retrieve cabang data
        $cabangRepo = new CabangRepository;
        $cabang = $cabangRepo->listCabang();

        $data = $this->listSlipGaji($nip, $year, null);

        $karyawan = KaryawanModel::where('nip', $nip)->first();

        return view('slip_gaji.slip', compact('data', 'cabang', 'karyawan'));
    }

    public function listSlipGaji($nip, $year, $cetak) {
        $slipRepository = new SlipGajiRepository;
        $data = $slipRepository->getSlip($nip, $year, $cetak);

        return $data;
    }

    function slipPDF() {
        $kantor = FacadesSession::get('kantor');
        $month = FacadesSession::get('month');
        $year = FacadesSession::get('year');
        $divisi = FacadesSession::get('divisi');
        $sub_divisi = FacadesSession::get('sub_divisi');
        $bagian = FacadesSession::get('bagian');
        $nip = FacadesSession::get('nip');
        $search = null;
        $page = null;

        $data = $this->listSlipGaji($kantor, $divisi, $sub_divisi, $bagian, $nip, $month, $year, $search, $page, null, 'cetak');

        return view('slip_gaji.tables.slip-pdf', ['data' => $data]);
    }

    public function cetakSlip(Request $request){
        $slipRepository = new SlipGajiRepository;
        $nip = $request->get('request_nip');
        $month = $request->get('request_month');
        $year = $request->get('request_year');
        $data = $slipRepository->getSlipCetak($nip, $month, $year);

        return view('slip_gaji.print.slip', compact('data'));
        $pdf = PDF::loadview('slip_gaji.print.slip', [
            'data' => $data
        ]);
        $fileName =  time() . '.' . 'pdf';
        return $pdf->download($fileName);
    }
}
