<?php

namespace App\Http\Controllers;

use App\Http\Requests\Karyawan\SuratPeringatanRequest;
use App\Http\Requests\PotonganRequest;
use App\Http\Requests\SuratPeringatan\HistoryRequest;
use App\Models\KaryawanModel;
use App\Models\SpModel;
use App\Repository\SuratPeringatanRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SuratPeringatanController extends Controller
{
    private SuratPeringatanRepository $repo;

    public function __construct()
    {
        $this->repo = new SuratPeringatanRepository;
    }

    public function index()
    {
        if (!auth()->user()->can('manajemen karyawan - reward & punishment - surat peringatan')) {
            return view('roles.forbidden');
        }
        $sps = SpModel::with('karyawan')->orderBy('tanggal_sp', 'DESC')->get();

        return view('karyawan.surat-peringatan.index', compact('sps'));
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

    public function store(PotonganRequest $request)
    {
        if (!auth()->user()->can('manajemen karyawan - reward & punishment - surat peringatan - create')) {
            return view('roles.forbidden');
        }
        $this->repo->store($request->all());

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
