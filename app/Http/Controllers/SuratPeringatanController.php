<?php

namespace App\Http\Controllers;

use App\Http\Requests\Karyawan\SuratPeringatanRequest;
use App\Models\SpModel;
use App\Repository\SuratPeringatanRepository;
use Illuminate\Http\Request;
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
        $sps = SpModel::with('karyawan')->get();

        return view('karyawan.surat-peringatan.index', compact('sps'));
    }

    public function show($id)
    {
        $sp = SpModel::findOrFail($id);

        return view('karyawan.surat-peringatan.show', compact('sp'));
    }

    public function create()
    {
        return view('karyawan.surat-peringatan.add');
    }

    public function store(SuratPeringatanRequest $request)
    {
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
}
