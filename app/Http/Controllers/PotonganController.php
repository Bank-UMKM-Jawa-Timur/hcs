<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExportForPotongan;
use App\Models\KaryawanModel;
use App\Models\PotonganModel;
use App\Repository\PotonganRepository;
use Carbon\Carbon;
use Exception;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PotonganController extends Controller
{
    private PotonganRepository $repo;

    public function __construct()
    {
        $this->repo = new PotonganRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Need permission
        if (!Auth::user()->can('penghasilan - import - potongan')) {
            return view('roles.forbidden');
        }
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $search = $request->get('q');
        $data = $this->repo->getPotongan($search, $limit, $page);

        return view('potongan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Need permission
        if (!Auth::user()->can('penghasilan - import - potongan - import')) {
            return view('roles.forbidden');
        }
        return view('potongan.add');
    }

    public function getKaryawanByNip(Request $request)
    {
        try {
            $nip = $request->get('nip');

            $nip_req = collect(json_decode($nip, true));
            $nip_id = $nip_req->pluck('nip')->toArray();

            $data = KaryawanModel::select('nama_karyawan', 'nip')
                ->whereIn('nip', $nip_id)
                ->whereNull('tanggal_penonaktifan')
                ->get();

            $response = $nip_req->map(function ($value) use ($data) {
                $nip = $value['nip'];
                $row = $value['row'];

                $nip_exist = $data->where('nip', $nip)->first();

                return [
                    'row' => $row,
                    'nip' => $nip_exist ? $nip_exist->nip : $nip,
                    'cek_nip' => $nip_exist ? true : false,
                    'nama_karyawan' => $nip_exist ? $nip_exist->nama_karyawan : 'Karyawan Tidak Ditemukan',
                ];
            })->toArray();

            return response()->json($response);

            // return response($data);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function importPotongan(){
        // Need permission
        if (!Auth::user()->can('penghasilan - import - potongan - import')) {
            return view('roles.forbidden');
        }
        return view('potongan.import-potongan');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->repo->store($request->all());
        Alert::success('Berhasil menambahkan Potongan.');
        return redirect()->route('potongan.index');
    }

    public function importPotonganPost(Request $request){
        try {
            $kredit_koperasi = explode(',', $request->get('kredit_koperasi'));
            $iuran_koperasi = explode(',', $request->get('iuran_koperasi'));
            $kredit_pegawai = explode(',', $request->get('kredit_pegawai'));
            $iuran_ik = explode(',', $request->get('iuran_ik'));
            $nip = explode(',', $request->get('nip'));
            $total = count($nip);
            $bulan = $request->get('bulan');
            $bulanReq = ($bulan < 10) ? ltrim($bulan, '0') : $bulan;
            $tahun = $request->get('tahun');

            if ($nip) {
                if (is_array($nip)) {
                    for ($i = 0; $i < $total; $i++) {
                        // Insert
                        // DB::table('potongan_gaji')->insert([
                        //     'nip' => $nip[$i],
                        //     'kredit_koperasi' => $kredit_koperasi[$i],
                        //     'iuran_koperasi' => $iuran_koperasi[$i],
                        //     'kredit_pegawai' => $kredit_pegawai[$i],
                        //     'iuran_ik' => $iuran_ik[$i],
                        //     'created_at' => now(),
                        //     'updated_at' => now(),
                        // ]);

                        // Update
                        DB::table('potongan_gaji')->where('nip', $nip[$i])->update([
                            'nip' => $nip[$i],
                            'kredit_koperasi' => $kredit_koperasi[$i],
                            'iuran_koperasi' => $iuran_koperasi[$i],
                            'kredit_pegawai' => $kredit_pegawai[$i],
                            'iuran_ik' => $iuran_ik[$i],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            Alert::success('Success', 'Berhasil menyimpan data');
            return redirect()->back();
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
        //
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

    public function detail($bulan, $tahun){
        // Need permission
        if (!Auth::user()->can('penghasilan - import - potongan - detail')) {
            return view('roles.forbidden');
        }
        $limit = Request()->has('page_length') ? Request()->get('page_length') : 10;
        $search = Request()->get('q');

        $data = $this->repo->detailPotongan($bulan, $tahun, $limit, $search);
        return view('potongan.detail', [
            'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
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
        $filename = Carbon::now()->format('his').'template_import_potongan'.'.'.'xlsx';
        return Excel::download(new KaryawanExportForPotongan(), $filename);
    }

    public function getPotongan(Request $request)
    {
        $tahun = $request->get('tahun');

        $bulan = DB::table('potongan_gaji')
                    ->where('tahun', $tahun)
                    ->distinct()
                    ->get('bulan');
        if (count($bulan) > 0) {
            return response()->json($bulan);
        } else {
            return null;
        }
    }
}
