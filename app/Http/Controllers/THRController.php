<?php

namespace App\Http\Controllers;

use App\Http\Requests\THRRequest;
use App\Repository\PenghasilanTidakTeraturRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class THRController extends Controller
{
    private PenghasilanTidakTeraturRepository $repo;

    public function __construct()
    {
        $this->repo = new PenghasilanTidakTeraturRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Need permission
        return view('bonus.thr');
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
    public function store(THRRequest $request)
    {
        try{
            $this->repo->store($request->all());

            Alert::success('Berhasil', 'Berhasil menambahkan THR');
            return redirect()->route('thr.index');
        } catch(Exception $e){
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        } catch(QueryException $e){
            Alert::error('Terjadi kesalahan', $e->getMessage());
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
