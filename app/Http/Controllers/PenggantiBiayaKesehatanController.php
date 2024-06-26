<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenghasilanTidakTeratur\PenggantiBiayaKesehatanRequest;
use App\Repository\PenghasilanTidakTeraturRepository;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PenggantiBiayaKesehatanController extends Controller
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
    public function index()
    {
        // Need permission
        return view('penghasilan.import_tidak_teratur.pengganti_biaya_kesehatan');
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
    public function store(PenggantiBiayaKesehatanRequest $request)
    {
        $this->repo->store($request->all());

        Alert::success('Berhasil', 'menambahkan data pengganti biaya kesehatan.');
        return redirect()->route('pengganti-biaya-kesehatan.index');
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
