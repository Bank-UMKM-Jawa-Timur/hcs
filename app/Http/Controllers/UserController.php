<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Models\ModelHasRole;
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
        if (!auth()->user()->can('setting - master - user - create user')) {
            return view('roles.forbidden');
        }
        $request->validate([
            'username' => 'unique:users,username|required',
        ], 
        [
            'username.unique' => 'User sudah terdaftar.',
        ]);
        try {
            $karyawan = KaryawanModel::select('nama_karyawan', 'nip')->where('nip', $request->name)->first();

            $dataUser = New User();
            $dataUser->name = $karyawan->nama_karyawan;
            $dataUser->username = $request->name;
            $dataUser->email = $request->username;
            $dataUser->password = Hash::make($request->password);
            $dataUser->save();

            $dataRole = New ModelHasRole();
            $dataRole->role_id = $request->role;
            $dataRole->model_type = 'App\Models\User';
            $dataRole->model_id = $dataUser->id;
            $dataRole->save();

        } catch (\Throwable $th) {
            Alert::error("User Sudah Terdaftar");
            return redirect()->route('user.create');
        }
        
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
        $role = $this->param->getRole();
        $dataRoleId = ModelHasRole::where('model_id',$id)->first(); 
        return view('user.edit', [
            'data' => $data,
            'karyawan' => $karyawan,
            'role' => $role,
            'dataRoleId' => $dataRoleId,
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
        $karyawan = KaryawanModel::select('nama_karyawan', 'nip')->where('nip', $request->name)->first();
        $userCheck = User::where('username', $request->name)->first();
        if (empty($userCheck)) {
            $dataUser = User::where('id',$id)->first();
            $dataUser->name = $karyawan->nama_karyawan;
            $dataUser->username = $request->name;
            $dataUser->email = $request->username;
            $dataUser->password = Hash::make($request->password);
            $dataUser->save();
    
            ModelHasRole::where('model_id', $id)->update([
                'role_id' => $request->role,
                'model_type' => 'App\Models\User',
                'model_id' => $dataUser->id,
            ]);
        }else{
            Alert::error("User Sudah Terdaftar.");
            return redirect()->route('user.edit', $id);
        }
        
        Alert::success('Berhasil Merubah User.');
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
