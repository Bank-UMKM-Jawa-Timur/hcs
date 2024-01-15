<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\GajiPerBulanModel;
use App\Models\KaryawanModel;
use App\Models\TunjanganModel;
use App\Repository\PenghasilanTeraturRepository;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KaryawanExport;
use App\Exports\ExportVitamin;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class PenghasilanTeraturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Need permission
        if (!auth()->user()->can('penghasilan - import - penghasilan teratur')) {
            return view('roles.forbidden');
        }
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $search = $request->get('q');

        $repo = new PenghasilanTeraturRepository;
        $data = $repo->getPenghasilanTeraturImport($search, $limit, $page);
        return view('penghasilan-teratur.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
        if (!auth()->user()->can('penghasilan - import - penghasilan teratur - import')) {
            return view('roles.forbidden');
        }
        $penghasilan = TunjanganModel::where('kategori', 'teratur')->where('is_import', 1)->get();
        return view('penghasilan-teratur.create', compact('penghasilan'));
    }

    public function getKaryawanByEntitas(Request $request)
    {
        try {
            $nip = $request->get('nip');

            $nip_req = collect(json_decode($nip, true));
            $nip_id = $nip_req->pluck('nip')->toArray();
            $is_cabang = auth()->user()->hasRole('cabang');
            $is_pusat = auth()->user()->hasRole('kepegawaian');

            if ($is_pusat) {
                $data = KaryawanModel::select('nama_karyawan', 'nip', 'no_rekening')
                    // ->where('kd_cabang', auth()->user()->kd_cabang)
                    ->whereIn('nip', $nip_id)
                    ->whereNull('tanggal_penonaktifan')
                    ->get();
            }
            if($is_cabang){
                $data = KaryawanModel::select('nama_karyawan', 'nip', 'no_rekening')
                    ->where('kd_entitas', auth()->user()->kd_cabang)
                    ->whereIn('nip', $nip_id)
                    ->whereNull('tanggal_penonaktifan')
                    ->get();
            }

            $response = $nip_req->map(function ($value) use ($data) {
                $nip = $value['nip'];
                $row = $value['row'];
                $tanggal = Request()->get('tanggal');
                $id_tunjangan = Request()->get('id_tunjangan');


                $nip_exist = $data->where('nip', $nip)->first();

                $tunjangan = DB::table('transaksi_tunjangan AS tk')
                    ->select('m.nama_tunjangan')
                    ->join('mst_tunjangan AS m', 'm.id', 'tk.id_tunjangan')
                    ->where('tk.nip', $nip)
                    ->where('tk.id_tunjangan', $id_tunjangan)
                    ->whereMonth('tk.tanggal', date('m', strtotime($tanggal)))
                    ->whereYear('tk.tanggal', date('Y', strtotime($tanggal)))
                    ->first();

                return [
                    'row' => $row,
                    'nip' => $nip_exist ? $nip_exist->nip : $nip,
                    'cek_nip' => $nip_exist ? true : false,
                    'cek_tunjangan' => $tunjangan ? true : false,
                    'tunjangan' => $tunjangan,
                    'nama_karyawan' => $nip_exist ? $nip_exist->nama_karyawan : 'Karyawan Tidak Ditemukan',
                    'no_rekening' => $nip_exist ? $nip_exist->no_rekening : 'No Rek Tidak Ditemukan',
                ];
            })->toArray();
            return response()->json($response);

            // return response($data);
        } catch (Exception $e) {
            return $e;
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Need permission
        if (!auth()->user()->can('penghasilan - import - penghasilan teratur - import')) {
            return view('roles.forbidden');
        }
        DB::beginTransaction();
        try {
            $tanggalVal = GajiPerBulanModel::where('bulan', Carbon::now()->format('m'))
                ->where('tahun', Carbon::now()->format('Y'))
                ->first();

            if($tanggalVal != null){
                if(Carbon::now() > Carbon::parse($tanggalVal->created_at) && Carbon::now()->format('m') == Carbon::parse($tanggalVal->created_at)->format('m')){
                    Alert::error('Terjadi keselahan', 'Proses import tidak dapat dilakukan karena gaji bulan ini telah diproses.');
                    return redirect()->back();
                }
            }
            $id_tunjangan = $request->get('tunjangan');
            $nominal = explode(',', $request->get('nominal'));
            $nip = explode(',', $request->get('nip'));
            $total = count($nip);
            $bulan = $request->get('bulan');
            $bulanReq = ($bulan < 10) ? ltrim($bulan, '0') : $bulan;

            $tanggal = date('Y-m-d', strtotime(date('Y') . '-' . $bulan . '-' . date('d')));
            $tahun = date("Y", strtotime($tanggal));
            $kd_entitas = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : '000';

            if ($nip) {
                if (is_array($nip)) {
                    for ($i = 0; $i < $total; $i++) {
                        DB::table('transaksi_tunjangan')->insert([
                            'nip' => $nip[$i],
                            'nominal' => $nominal[$i],
                            'id_tunjangan' => $id_tunjangan,
                            'tanggal' => $tanggal,
                            'bulan' => $bulanReq,
                            'kd_entitas' => $kd_entitas,
                            'is_lock' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $gaji = GajiPerBulanModel::where('nip', $nip[$i])
                                                ->where('bulan', $bulanReq)
                                                ->where('tahun', $tahun)
                                                ->first();
                        $tunjangan = TunjanganModel::find($id_tunjangan);
                        if ($gaji) {
                            if ($tunjangan->nama_tunjangan == 'Transport') {
                                $gaji->update([
                                    'tj_transport' => $nominal[$i] + $gaji->tj_transport
                                ]);
                            } elseif ($tunjangan->nama_tunjangan == 'Pulsa') {
                                $gaji->update([
                                    'tj_pulsa' => $nominal[$i] + $gaji->tj_pulsa
                                ]);
                            } elseif ($tunjangan->nama_tunjangan == 'Vitamin') {
                                $gaji->update([
                                    'tj_vitamin' => $nominal[$i] + $gaji->tj_vitamin
                                ]);
                            } elseif ($tunjangan->nama_tunjangan == 'Uang Makan') {
                                $gaji->update([
                                    'uang_makan' => $nominal[$i] + $gaji->uang_makan
                                ]);
                            }
                        }
                    }
                }
            }

            Alert::success('Success', 'Berhasil menyimpan data');
            DB::commit();

            return redirect()->route('penghasilan.import-penghasilan-teratur.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return back();
        }
    }

    public function lock(Request $request){
        if (!auth()->user()->can('penghasilan - lock - penghasilan teratur')) {
            return view('roles.forbidden');
        }
        $repo = new PenghasilanTeraturRepository;
        $repo->lock($request->all());
        Alert::success('Berhasil lock tunjangan.');
        return redirect()->route('penghasilan.import-penghasilan-teratur.index');
    }

    public function unlock(Request $request){
        if (!auth()->user()->can('penghasilan - unlock - penghasilan teratur')) {
            return view('roles.forbidden');
        }
        $repo = new PenghasilanTeraturRepository;
        $repo->unlock($request->all());
        Alert::success('Berhasil unlock tunjangan.');
        return redirect()->route('penghasilan.import-penghasilan-teratur.index');
    }

    public function editTunjangan(Request $request){
        $id = $request->get('idTunjangan');
        $tanggal = $request->get('createdAt');
        $bulan = $request->get('bulan');
        $repo = new PenghasilanTeraturRepository;
        $penghasilan = $repo->TunjanganSelected($id);
        return view('penghasilan-teratur.edit', [
            'penghasilan' => $penghasilan,
            'old_id' =>$id,
            'old_tanggal' => $tanggal,
            'bulan' => $bulan
        ]);
    }

    public function editTunjanganPost(Request $request){
        try {
            $id_tunjangan = $request->get('tunjangan');
            $nominal = explode(',', $request->get('nominal'));
            $nip = explode(',', $request->get('nip'));
            $total = count($nip);
            $bulan = $request->get('bulan');
            $bulanReq = ($bulan < 10) ? ltrim($bulan, '0') : $bulan;

            $tanggal = date('Y-m-d', strtotime(date('Y') . '-' . $bulan . '-' . date('d')));
            $tahun = date("Y", strtotime($tanggal));

            $old_tunjangan = $request->get('old_tunjangan');
            $old_tanggal = $request->get('old_tanggal');
            $created_at = $request->get('created_at');
            // dd($old_tunjangan, $old_tanggal, $created_at);

            DB::table('transaksi_tunjangan')
            ->where('id_tunjangan', $old_tunjangan)
            ->where(DB::raw('DATE(transaksi_tunjangan.tanggal)'), $old_tanggal)
            ->where('created_at', $created_at)
            ->delete();

            if ($nip) {
                if (is_array($nip)) {
                    for ($i = 0; $i < $total; $i++) {
                        DB::table('transaksi_tunjangan')->insert([
                            'nip' => $nip[$i],
                            'nominal' => $nominal[$i],
                            'id_tunjangan' => $id_tunjangan,
                            'tahun' => date('Y'),
                            'tanggal' => $tanggal,
                            'bulan' => $bulanReq,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $gaji = GajiPerBulanModel::where('nip', $nip[$i])
                            ->where('bulan', $bulanReq)
                            ->where('tahun', $tahun)
                            ->first();
                        $tunjangan = TunjanganModel::find($old_tunjangan);
                        if ($gaji) {
                            if ($tunjangan->nama_tunjangan == 'Transport') {
                                $gaji->update([
                                    'tj_transport' => $nominal[$i] + $gaji->tj_transport
                                ]);
                            } elseif ($tunjangan->nama_tunjangan == 'Pulsa') {
                                $gaji->update([
                                    'tj_pulsa' => $nominal[$i] + $gaji->tj_pulsa
                                ]);
                            } elseif ($tunjangan->nama_tunjangan == 'Vitamin') {
                                $gaji->update([
                                    'tj_vitamin' => $nominal[$i] + $gaji->tj_vitamin
                                ]);
                            } elseif ($tunjangan->nama_tunjangan == 'Uang Makan') {
                                $gaji->update([
                                    'uang_makan' => $nominal[$i] + $gaji->uang_makan
                                ]);
                            }
                        }
                    }
                }
            }

            Alert::success('Success', 'Berhasil menyimpan data');

            return redirect()->route('penghasilan.import-penghasilan-teratur.index');
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            Alert::error('Error', $e->getMessage());
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    public function details($idTunjangan, Request $request)
    {
        $tanggal = $request->tanggal;
        $createdAt = $request->createdAt;
        // Need permission
        if (!auth()->user()->can('penghasilan - import - penghasilan teratur - detail')) {
            return view('roles.forbidden');
        }
        $limit = Request()->has('page_length') ? Request()->get('page_length') : 10;
        $page = Request()->has('page') ? Request()->get('page') : 1;

        $search = Request()->get('q');
        $repo = new PenghasilanTeraturRepository;
        $data = $repo->getDetailTunjangan($idTunjangan, $tanggal, $createdAt, $search, $limit);

        return view('penghasilan-teratur.detail', [
            'data' => $data,
            'tunjangan' => $repo->getNamaTunjangan($idTunjangan)
        ]);
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

    public function templateExcel()
    {
        // Need permission
        $filename = Carbon::now()->format('his').'_template_import_penghasilan_teratur'.'.'.'xlsx';
        return Excel::download(new KaryawanExport(), $filename);
    }

    public function cetakVitamin(Request $request)
    {
        if (!auth()->user()->can('penghasilan - import - penghasilan teratur - download vitamin')) {
            return view('roles.forbidden');
        }
        if (!$request->get('bulan')) {
            Alert::warning('Peringatan', 'Bulan harus dipilih.');
            return back();
        }
        if (!$request->get('tahun')) {
            Alert::warning('Peringatan', 'Tahun harus dipilih.');
            return back();
        }

        return Excel::download(new ExportVitamin(), 'RINCIAN_PENGGANTI_UANG_VITAMIN_PEGAWAI.xlsx');
    }
}
