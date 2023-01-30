<?php

namespace App\Http\Controllers;

use App\Http\Requests\PejabatSementaraRequest;
use App\Models\JabatanModel;
use App\Models\KaryawanModel;
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

    public function history(Request $request)
    {
        $pjs = $karyawan = null;

        if ($request->kategori) {
            $pjs = PjsModel::with('karyawan');
            ($request->kategori == 'aktif') ?
                $pjs->whereNull('tanggal_berakhir') :
                $pjs->whereNotNull('tanggal_berakhir');

            $pjs = $pjs->get();
        }

        if ($request->nip) {
            $karyawan = KaryawanModel::find($request->nip);
            $pjs = PjsModel::with('karyawan')
                ->where('nip', $request->nip)
                ->get();
        }

        return view('pejabat-sementara.history', compact('pjs', 'karyawan'));
    }

    public function destroy(Request $request, $id)
    {
        $pjs = PjsModel::findOrFail($id);
        $this->repo->deactivate($pjs, $request->tanggal_berakhir);
        Alert::success('Berhasil menonaktifkan PJS');

        return redirect()->back();
    }
}
