<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExport;
use App\Models\KaryawanModel;
use App\Models\TunjanganModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Repository\PenghasilanTidakTeraturRepository;
use Maatwebsite\Excel\Facades\Excel;

class BonusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     private PenghasilanTidakTeraturRepository $repo;

     public function __construct()
     {
         $this->repo = new PenghasilanTidakTeraturRepository;
     }

    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $search = $request->get('q');
        $data = $this->repo->getDataBonus($search, $limit, $page);
        return view('bonus.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tunjangan = TunjanganModel::select('nama_tunjangan','id')->where('kategori','bonus')->where('is_import',1)->get();
        return view('bonus.import',[
            'data_tunjangan' => $tunjangan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'upload_csv' => 'required|mimes:xlsx,xls',
            'nip' => 'required',
            'kategori_bonus' => 'required',
            'nominal' => 'required',
        ],[
            'kategori_bonus' => ':attribute harus terisi.'
        ],[
            'kategori_bonus' => 'Kategori',
            'nip' => 'NIP'
        ]);
        try {
            \DB::beginTransaction();
            $data_nominal = explode(',',$request->get('nominal'));
            $data_nip = explode(',',$request->get('nip'));
            $tunjangan = TunjanganModel::where('id',$request->get('kategori_bonus'))->first();
            for ($i=0; $i < count($data_nip); $i++) {
                DB::table('penghasilan_tidak_teratur')
                ->insert([
                    'nip' => $data_nip[$i],
                    'id_tunjangan' => $tunjangan->id,
                    'nominal' => $data_nominal[$i],
                    'bulan' => Carbon::parse($request->get('tanggal'))->format('m'),
                    'tahun' => Carbon::parse($request->get('tanggal'))->format('Y'),
                    'created_at' => now()
                ]);

            }
            \DB::commit();

            Alert::success('Berhasil', 'Berhasil menambahkan data penghasilan tambahan');
            return redirect()->route('bonus.index');
        } catch (Exception $th) {
            \DB::rollBack();
            return $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $search = $request->get('q');
        // $data = $this->repo->getDataBonus($search, $limit, $page);
        $data = $this->repo->getDetailBonus($search, $limit,$page, $id);
        // return $data;
        return view('bonus.detail',['data' => $data]);
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

    function fileExcel() {
        return Excel::download(new KaryawanExport,'template_import_bonus.xlsx');
    }
}
