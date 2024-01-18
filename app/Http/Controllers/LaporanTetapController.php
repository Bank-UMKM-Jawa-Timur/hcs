<?php

namespace App\Http\Controllers;

use App\Exports\RekapTetapExport;
use App\Models\CabangModel;
use App\Repository\LaporanTetapRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class LaporanTetapController extends Controller
{
    private $repo;
    private $cabang;

    public function __construct()
    {
        $this->repo = new LaporanTetapRepository;
        $this->cabang = CabangModel::get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $year = $request->get('tahun') ?? null;
        Session::put('year', $year);

        $kantor = null;
        if(auth()->user()->hasRole('cabang')){
            $kantor = auth()->user()->kd_cabang;
        } else {
            $kantor = 'pusat';
        }
        Session::put('kantor', $kantor);
        $search = $request->get('q');
        $data = $request->has('tahun') ? $this->repo->get($kantor, $search, $limit, false, intval($year)) : null;
        $footer = $request->has('tahun') ? $this->repo->getTotal($kantor, $search, $limit, false, intval($year)) : null;
        $cabang = $this->cabang;
        
        return view('rekap-tetap.index', [
            'cabang' => $cabang,
            'data' => $data,
            'grandTotal' => $footer
        ]);
    }

    public function cetak(){
        $year = Session::get('year');
        $kantor = Session::get('kantor');
        $limit = null;
        $search = null;
        $data = $this->repo->get($kantor, $search, $limit, true, intval($year));

        $showKantor = '';
        if($kantor == 'pusat')
            $showKantor = 'Kantor Pusat';
        else if($kantor == 'keseluruhan')
            $showKantor = 'Keseluruhan';
        else 
            $showKantor = 'Kantor ' . CabangModel::where('kd_cabang', $kantor)->first()?->nama_cabang;

        $filename = 'Rekap Tetap ' . $showKantor . ' Tahun ' . $year;
        return Excel::download(new RekapTetapExport($data), $filename . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
}
