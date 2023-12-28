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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PejabatSementaraController extends Controller
{
    private PejabatSementaraRepository $repo;

    public function __construct()
    {
        $this->repo = new PejabatSementaraRepository;
    }

    public function index()
    {
        if (!auth()->user()->can('manajemen karyawan - data penjabat sementara')) {
            return view('roles.forbidden');
        }
        $pjs = PjsModel::with(['karyawan', 'jabatan'])->get();
        return view('pejabat-sementara.index', compact('pjs'));
    }

    public function create()
    {
        if (!auth()->user()->can('manajemen karyawan - tambah penjabat sementara')) {
            return view('roles.forbidden');
        }
        $jabatan = JabatanModel::whereNotIn('kd_jabatan', [
            'IKJP', 'NST', 'ST',
        ])->get();

        return view('pejabat-sementara.add', compact('jabatan'));
    }

    public function store(PejabatSementaraRequest $request)
    {
        if (!auth()->user()->can('manajemen karyawan - tambah penjabat sementara')) {
            return view('roles.forbidden');
        }
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
        if (!auth()->user()->can('histori - penjabat sementara')) {
            return view('roles.forbidden');
        }
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
        // Need permission
        if (!auth()->user()->can('manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan')) {
            return view('roles.forbidden');
        }
        $pjs = PjsModel::findOrFail($id);
        if($pjs->tanggal_mulai < $request->tgl_berakhir){
            $this->repo->deactivate($pjs, $request->tgl_berakhir);
            Alert::success('Berhasil menonaktifkan PJS');
        } else{
            Alert::error('Tanggal penonaktifan harus lebih dari tanggal mulai.');
        }

        return redirect()->back();
    }
}
