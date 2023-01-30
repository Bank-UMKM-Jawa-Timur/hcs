<?php

namespace App\Http\Controllers;

use App\Http\Requests\PejabatSementaraRequest;
use App\Models\JabatanModel;
use App\Models\PjsModel;
use App\Repository\PejabatSementaraRepository;
use App\Service\EntityService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PejabatSementaraController extends Controller
{
    private PejabatSementaraRepository $repo;

    public function __construct()
    {
        $this->repo = new PejabatSementaraRepository;
    }

    public function index()
    {
        $pjs = PjsModel::with(['karyawan', 'jabatan'])->get();
        return view('pejabat-sementara.index', compact('pjs'));
    }

    public function create()
    {
        $jabatan = JabatanModel::all();
        return view('pejabat-sementara.add', compact('jabatan'));
    }

    public function store(PejabatSementaraRequest $request)
    {
        $request->merge([
            'kd_entitas' => EntityService::getEntityFromRequest($request),
        ]);

        $this->repo->store(
            $request->all(),
            $request->file('file_sk')
        );

        Alert::success('Berhasil menambahkan PJS');
        return redirect()->route('pejabat-sementara.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        $pjs = PjsModel::findOrFail($id);
        $this->repo->deactivate($pjs, $request->tanggal_berakhir);
        Alert::success('Berhasil menonaktifkan PJS');

        return redirect()->back();
    }
}
