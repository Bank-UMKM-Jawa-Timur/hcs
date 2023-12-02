<?php

namespace App\Http\Controllers;

use App\Imports\MigrasiImport;
use App\Imports\MigrasiJabatanImport;
use App\Imports\MigrasiPJSImport;
use App\Imports\MigrasiSPImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class MigrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('migrasi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('migrasi.add');
    }

    public function migrasiJabatan()
    {
        if (!Auth::user()->can('migrasi - jabatan')) {
            return view('roles.forbidden');
        }
        return view('migrasi.jabatan');
    }

    public function migrasiPJS()
    {
        if (!Auth::user()->can('migrasi - penjabat sementara')) {
            return view('roles.forbidden');
        }
        return view('migrasi.pjs');
    }

    public function migrasiSP()
    {
        if (!Auth::user()->can('migrasi - surat peringatan')) {
            return view('roles.forbidden');
        }
        return view('migrasi.sp');
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
        $tipe = $request->tipe;

        if($tipe == 'jabatan'){
            $import = new MigrasiJabatanImport;
        } else if($tipe == 'pjs'){
            $import = new MigrasiPJSImport;
        } else if($tipe == 'sp'){
            $import = new MigrasiSPImport;
        }

        $import = $import->import($file);

        Alert::success('Berhasil', 'Berhasil mengimport data excel');
        if($tipe == 'jabatan'){
            return redirect()->route('migrasiJabatan');
        } else if($tipe == 'pjs'){
            return redirect()->route('migrasiPJS');
        } else if($tipe == 'sp'){
            return redirect()->route('migrasiSP');
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
