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

class PenghasilanTeraturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $search = $request->get('q');

        $data = new PenghasilanTeraturRepository;
        return view('penghasilan-teratur.index',['data' => $data->getPenghasilanTeraturImport($search,$limit,$page)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $penghasilan = TunjanganModel::where('kategori', 'teratur')->where('is_import', 1)->get();
        return view('penghasilan-teratur.create', compact('penghasilan'));
    }

    public function getKaryawanByEntitas(Request $request){
        $nip = $request->nip;
        $tanggal = $request->tanggal;
        $tanggal = $request->tanggal;
        $id_tunjangan = $request->id_tunjangan;
        $data = KaryawanModel::where('nip', $nip)->first();
        $tunjangan = DB::table('tunjangan_karyawan AS tk')
                        ->select('m.nama_tunjangan')
                        ->join('mst_tunjangan AS m', 'm.id', 'tk.id_tunjangan')
                        ->where('tk.nip', $nip)
                        ->where('tk.id_tunjangan', $id_tunjangan)
                        ->whereMonth('tk.created_at', date('m', strtotime( $tanggal)))
                        ->whereYear('tk.created_at', date('Y', strtotime($tanggal)))
                        ->first();
        if ($data) {
            return response()->json([
                'status' => 'Success',
                'message' => 'success',
                'data' => $data,
                'tunjangan' => $tunjangan
            ]);
        } else {
            return response()->json([
                'status' => 'Success',
                'message' => 'data not found',
                'data' => null,
                'tunjangan' => null
            ]);
        }
    }
    public function getKaryawanSearch(Request $request){
        $data = KaryawanModel::select("nama_karyawan", "nip")
                        ->where('nip', 'LIKE', '%'. $request->get('search'). '%')
                        ->get();
        foreach($data as $item ){
        $usersArray[] = array(
            "label" => $item->nip.'-'.$item->nama_karyawan,
            "value" => $item->nip,
            "nama" => $item->nama_karyawan,
        );
        }

        return response()->json($usersArray);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        try {
            $total = $request->get('number');
            $id_tunjangan = $request->get('penghasilan');
            $nip = $request->get('nip');
            $nominal = str_replace([' ', '.', "\u{A0}"], '', $request->get('nominal'));
            $tanggal = date('Y-m-d H:i:s');
            $bulan = date("m", strtotime($tanggal));

            if ($total) {
                if (is_array($total)) {
                    for ($i=0; $i < count($total); $i++) {
                        $dataAda = DB::table('tunjangan_karyawan')->where('nip', $nip[$i])
                            ->where('id_tunjangan', $id_tunjangan[$i])
                            ->where('created_at', $tanggal)
                            ->count();

                        $dataTidakDitemukan = KaryawanModel::where('nip', $nip[$i])->count();

                        if ($dataAda > 1 && $dataTidakDitemukan < 1) {
                            continue;
                        }

                        DB::table('tunjangan_karyawan')->insert([
                            'nip' => $nip[$i],
                            'nominal' => $nominal[$i],
                            'id_tunjangan' => $id_tunjangan[$i],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            Alert::success('Success', 'Berhasil menyimpan data');
            return redirect()->route('penghasilan.import-penghasilan-teratur.index');
            // return redirect()->back();

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

    public function details($idTunjangan, $createdAt)
    {
        $limit = Request()->has('page_length') ? Request()->get('page_length') : 10;
        $page = Request()->has('page') ? Request()->get('page') : 1;

        $search = Request()->get('q');
        $repo = new PenghasilanTeraturRepository;
        return view('penghasilan-teratur.detail', [
            'data' => $repo->getDetailTunjangan($idTunjangan, $createdAt, $search, $limit),
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

    public function templateExcel(){
        return Excel::download(new KaryawanExport, 'template_import_penghasilan_teratur.xlsx');
    }
}
