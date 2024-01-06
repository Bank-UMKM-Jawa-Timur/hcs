<?php

namespace App\Http\Controllers;

use App\Http\Requests\Karyawan\SuratPeringatanRequest;
use App\Http\Requests\PotonganRequest;
use App\Http\Requests\SuratPeringatan\HistoryRequest;
use App\Models\KaryawanModel;
use App\Models\SpModel;
use App\Repository\SuratPeringatanRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SuratPeringatanController extends Controller
{
    private SuratPeringatanRepository $repo;

    public function __construct()
    {
        $this->repo = new SuratPeringatanRepository;
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('manajemen karyawan - reward & punishment - surat peringatan')) {
            return view('roles.forbidden');
        }
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $karyawanRepo = new SuratPeringatanRepository();
        $search = $request->get('q');
        $data = $karyawanRepo->getAllSuratPeringatan($search, $limit, $page);

        return view('karyawan.surat-peringatan.index', [
            'sps' => $data,
        ]);
    }

    public function show($id)
    {
        if (!auth()->user()->can('manajemen karyawan - reward & punishment - surat peringatan - detail')) {
            return view('roles.forbidden');
        }
        $sp = SpModel::findOrFail($id);

        return view('karyawan.surat-peringatan.show', compact('sp'));
    }

    public function create()
    {
        if (!auth()->user()->can('manajemen karyawan - reward & punishment - surat peringatan - create')) {
            return view('roles.forbidden');
        }
        return view('karyawan.surat-peringatan.add');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('manajemen karyawan - reward & punishment - surat peringatan - create')) {
            return view('roles.forbidden');
        }
        try{
            DB::beginTransaction();
            $this->repo->store($request->all());
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->back();
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return redirect()->back();
        }

        Alert::success('Berhasil menambahkan SP');
        return redirect()->route('surat-peringatan.index');
    }

    public function edit($id)
    {
        $sp = SpModel::findOrFail($id);
        return view('karyawan.surat-peringatan.edit', compact('sp'));
    }

    public function update(SuratPeringatanRequest $request, $id)
    {
        $sp = SpModel::findOrFail($id);
        $sp->update($request->all());

        Alert::success('Berhasil mengedit SP');
        return redirect()->route('surat-peringatan.index');
    }

    public function history(HistoryRequest $request)
    {
        if (!auth()->user()->can('histori - surat peringatan')) {
            return view('roles.forbidden');
        }
        return view('karyawan.surat-peringatan.history', [
            'history' => $this->repo->report($request->only(['tahun', 'nip', 'first_date', 'end_date'])),
            'firstData' => SpModel::oldest('tanggal_sp')->first(),
            'karyawan' => KaryawanModel::find($request->nip),
            'request' => $request,
        ]);
    }
}
