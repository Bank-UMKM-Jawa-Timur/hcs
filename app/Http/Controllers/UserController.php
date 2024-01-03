<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Models\ModelHasRole;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repository\UserRepository;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Support\Facades\DB;
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
        $cabang = $this->param->getCabang();
        return view('user.create', [
            'karyawan' => $karyawan,
            'role' => $role,
            'cabang' => $cabang
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

        $this->validate($request, [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'role' => 'not_in:0'
            ], [
                'required' => ':attribute harus diisi.',
                'unique' => ':attribute telah digunakan.',
                'not_in' => ':attribute harus dipilih.'
            ],[
                'name' => 'Nama',
                'email' => 'Email',
                'role' => 'Role'
            ]
        );

        DB::beginTransaction();
        try {
            $dataUser = new User();
            $dataUser->name = $request->name;
            $dataUser->username = $request->name;
            $dataUser->email = $request->email;
            if ($request->role == 4) {
                $dataUser->kd_cabang = $request->data_cabang;
            }
            $dataUser->password = Hash::make('12345678');
            $dataUser->save();

            $dataRole = new ModelHasRole();
            $dataRole->role_id = $request->role;
            $dataRole->model_type = 'App\Models\User';
            $dataRole->model_id = $dataUser->id;
            $dataRole->save();

            DB::commit();
            Alert::success('Berhasil Menambahkan User.');
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error($e->getMessage());
            return redirect()->back();
        }
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
        $cabang = $this->param->getCabang();
        return view('user.edit', [
            'data' => $data,
            'karyawan' => $karyawan,
            'role' => $role,
            'dataRoleId' => $dataRoleId,
            'cabang' => $cabang
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
        $user = User::find($id);
        $uniqueEmail = $request->email && $request->email != $user->email ? '|unique:users,email' : '';

        $this->validate($request, [
                'name' => 'required',
                'email' => 'required'.$uniqueEmail,
                'role' => 'not_in:0'
            ], [
                'required' => ':attribute harus diisi.',
                'unique' => ':attribute telah digunakan.',
                'not_in' => ':attribute harus dipilih.'
            ],[
                'name' => 'Nama',
                'email' => 'Email',
                'role' => 'Role'
            ]
        );

        DB::beginTransaction();
        try {
            $dataUser = User::where('id',$id)->first();
            $dataUser->name = $request->name;
            $dataUser->email = $request->email;
            if ($request->role == 4) {
                $dataUser->kd_cabang = $request->data_cabang;
            }else{
                $dataUser->kd_cabang = null;
            }
            $dataUser->password = Hash::make($request->password);
            $dataUser->save();

            ModelHasRole::where('model_id', $id)->update([
                'role_id' => $request->role,
                'model_type' => 'App\Models\User',
                'model_id' => $dataUser->id,
            ]);

            DB::commit();
            Alert::success('Berhasil Merubah User.');
            return redirect()->route('user.index');
        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('Terjadi Kesalahan '.$e->getMessage());
            return redirect()->route('user.edit');
        }

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
        if (!auth()->user()->can('setting - master - user - delete user')) {
            return view('roles.forbidden');
        }
        DB::beginTransaction();
        try{
            DB::table('users')
                ->where('id', $id)
                ->delete();
            DB::table('model_has_roles')
                ->where('model_id', $id)
                ->delete();

            DB::commit();

            Alert::success('Berhasil', 'Berhasil Menghapus Data User.');
            return redirect()->route('user.index');
        } catch(Exception $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('user.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            DB::rollBack();
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('user.index')->withStatus($e->getMessage());
        }
    }


    public function resetUser($id)
    {
        $user = User::select('name','username','email')->where('id',$id)->first();

        $dataUser = User::where('id',$id)->first();
        $dataUser->password = Hash::make($user->username);
        $dataUser->save();

        Alert::success('Berhasil Reset Password User.');
        return redirect()->route('user.index');
    }

}
