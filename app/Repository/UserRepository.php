<?php

namespace App\Repository;

use App\Models\CabangModel;
use App\Models\KaryawanModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserRepository
{
    private $param;
    public function getListUser($search, $limit=10, $page=1) {
        $this->param['data'] = DB::table('users')
                    ->join('model_has_roles', function ($join) {
                        $join->on('model_has_roles.model_id', '=', 'users.id')
                            ->where(DB::raw('CAST(model_has_roles.model_id AS CHAR)'), '=', DB::raw('CAST(users.id AS CHAR)'));
                    })
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->leftJoin('mst_cabang', 'users.kd_cabang', '=', 'mst_cabang.kd_cabang')
                    ->select(
                        'users.id',
                        'users.name as name_user',
                        'users.email',
                        'users.username',
                        'users.kd_cabang',
                        'mst_cabang.nama_cabang',
                        'users.first_login',
                        'roles.name as name_role',
                        'model_has_roles.model_id'
                    )
                    ->when($search, function ($query) use ($search) {
                        $query->where('users.name', 'like', "%$search%")
                        ->orWhere('users.email', 'like', "%$search%")
                        ->orWhere('roles.name', 'like', "%$search%");
                    })
                    ->orderBy('roles.id')
                    ->orderBy('users.kd_cabang')
                    ->paginate($limit);

        return $this->param['data'];
    }

    public function getDataKaryawan(){
        $this->param['data'] = KaryawanModel::select('nip', 'nama_karyawan')
        ->orderBy('nip')
        ->whereNull('tanggal_penonaktifan')
        ->get();

        return $this->param['data'];
    }

    public function getRole(){
        $this->param['data'] = Role::all();

        return $this->param['data'];
    }

    public function store(array $data){
        $nama = KaryawanModel::where('nip', $data['name'])->first();
        return User::create([
            'name' => $data['name'],
            'username' => $data['name'],
            'email' => $data['username'],
            'password' =>  Hash::make($data['password']),
        ]);
    }

    public function dataByid($id){
        $this->param['data'] = User::find($id);
        return $this->param['data'];
    }

    public function update(array $data, $id){
        return User::where('id', $id)->update([
            'name' => $data['name'],
            'username' => $data['name'],
            'email' => $data['username'],
            'password' =>  Hash::make($data['password']),
        ]);
    }

    public function getCabang(){
        return CabangModel::select('kd_cabang', 'nama_cabang', 'id_kantor')->get();
    }
}
