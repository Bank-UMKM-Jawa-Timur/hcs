<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TunjanganKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('tunjangan_karyawan')
            ->join('tunjangan', 'tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
            ->join('karyawan', 'karyawan.nip', '=', 'tunjangan_karyawan.nip')
            ->select(
                'tunjangan_karyawan.id',
                'karyawan.nip',
                'karyawan.nama_karyawan',
                'tunjungan.nama_tunjangan',
                'tunjangan.nominal',
            )
            ->get();

        return view('tunjangan_karyawan.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tunjangan_karyawan.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

        } catch(Exception $e){

        } catch(QueryException $e){

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
        $data = DB::table('tunjangan_karyawan')
            ->where('id', $id)
            ->join('tunjangan', 'tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
            ->join('karyawan', 'karyawan.nip', '=', 'tunjangan_karyawan.nip')
            ->first();

        return view('tunjangan_karyawan.edit', ['data' => $data]);
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
        try{

        } catch(Exception $e){

        } catch(QueryException $e){
            
        }
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
