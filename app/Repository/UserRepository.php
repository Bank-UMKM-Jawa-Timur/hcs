<?php

namespace App\Repository;

use App\Models\KaryawanModel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    private $param;
    public function getListUser($search, $limit=10, $page=1) {
        $this->param['data'] = User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%");
        })
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
}
