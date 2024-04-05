<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
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
         $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;
        $search = $request->has('q') ? str_replace("'", "\'", $request->get('q')) : null;
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

            // Record to log activity
            $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
            $namaKaryawan = DB::table('mst_karyawan')->where('nip', $request->nip)->first()?->nama_karyawan;
            $activity = "Pengguna <b>$name</b> menambahkan surat peringatan kepada karyawan atas nama <b>$namaKaryawan</b>.";
            LogActivity::create($activity);

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

        // Record to log activity
        $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
        $activity = "Pengguna <b>$name</b> melihat history surat peringatan";
        LogActivity::create($activity);

        return view('karyawan.surat-peringatan.history', [
            'history' => $this->repo->report($request->only(['tahun', 'nip', 'first_date', 'end_date'])),
            'firstData' => SpModel::oldest('tanggal_sp')->first(),
            'karyawan' => KaryawanModel::find($request->nip),
            'request' => $request,
        ]);
    }
}
