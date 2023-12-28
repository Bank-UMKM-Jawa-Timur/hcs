<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;


class UserController extends Controller
{
    public $param;
    public function __construct()
    {
        // $permissionNames = auth()->user()->getAllPermissions()->pluck('name');
        // $this->middleware(['auth','permission:'.getPermission($permissionNames)]);
        $this->param = new UserRepository();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('setting - master - user')) {
            return view('roles.forbidden');
        }
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;

        $search = $request->get('q');

        $data = $this->param->getListUser($search, $limit, $page);
        return view('user.index', [
            'data' => $data,
        ]    
    );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('setting - master - user - create user')) {
            return view('roles.forbidden');
        }
        $karyawan = $this->param->getDataKaryawan();
        return view('user.create', [
            'karyawan' => $karyawan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('setting - master - user - create user')) {
            return view('roles.forbidden');
        }
        $nama = KaryawanModel::select('nama_karyawan')->where('nip', $request->name)->first();
        User::create([
            'name' => $nama->nama_karyawan,
            'username' => $nama->nama_karyawan,
            'email' => $request->username,
            'password' =>  Hash::make($request->password),
        ]);
        Alert::success('Berhasil Menambahkan User.');
        return redirect()->route('user.index');
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
        if (!auth()->user()->can('setting - master - user - edit user')) {
            return view('roles.forbidden');
        }
        $data = $this->param->dataByid($id);
        $karyawan = $this->param->getDataKaryawan();
        return view('user.edit', [
            'data' => $data,
            'karyawan' => $karyawan
        ]);
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
        if (!auth()->user()->can('setting - master - user - edit user')) {
            return view('roles.forbidden');
        }
        $nama = KaryawanModel::select('nama_karyawan')->where('nip', $request->name)->first();
        User::where('id', $id)->update([
            'name' => $nama->nama_karyawan,
            'username' => $nama->nama_karyawan,
            'email' => $request->username,
            'password' =>  Hash::make($request->name),
        ]);
        Alert::success('Berhasil Mengubah User.');
        return redirect()->route('user.index');
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
