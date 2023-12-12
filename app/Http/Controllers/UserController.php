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
        $karyawan = KaryawanModel::where('nip', '01474')->first();
        $role = $karyawan->getRoleNames();
        // return $role;s
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
        $karyawan = $this->param->getDataKaryawan();
        $role = $this->param->getRole();
        return view('user.create', [
            'karyawan' => $karyawan,
            'role' => $role
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
        // return $request;
        $karyawan = KaryawanModel::select('nama_karyawan', 'nip')->where('nip', $request->name)->first();
        User::create([
            'name' => $karyawan->nama_karyawan,
            'username' => $request->name,
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
        $data = $this->param->dataByid($id);
        $karyawan = $this->param->getDataKaryawan();
        $role = $this->param->getRole();
        return view('user.edit', [
            'data' => $data,
            'karyawan' => $karyawan,
            'role' => $role
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
        // return $request;
        $nama = KaryawanModel::select('nama_karyawan')->where('nip', $request->name)->first();
        User::where('id', $id)->update([
            'name' => $nama->nama_karyawan,
            'username' => $nama->nama_karyawan,
            'email' => $request->username,
        ]);
        Alert::success('Berhasil Mengubah User.');
        return redirect()->route('user.index');
    }

    public function resetPass($id) {
        $user = User::find($id);
        return view('user.reset-password', compact('user'));
    }

    public function updatePass(Request $request, $id) {
        $request->validate([
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $pass = Hash::make(Request()->password);

        User::where('id', $id)->update([
            'password' => $pass
        ]);

        Alert::success('Berhasil Mengubah Password.');
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
