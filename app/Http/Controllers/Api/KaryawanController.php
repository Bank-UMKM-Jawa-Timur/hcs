<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\KaryawanResource;
use App\Models\KaryawanModel;
use Exception;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function __invoke(Request $request)
    {
        $karyawan = KaryawanModel::with('jabatan', 'bagian');

        if ($request->nip) $karyawan->where('nip', $request->nip);
        if ($request->nama) $karyawan->where('nama', $request->nama);

        if ($karyawan = $karyawan->first()) {
            return KaryawanResource::make($karyawan);
        }

        return response()->json([
            'error' => 404,
            'message' => 'Karyawan tidak ditemukan',
        ]);
    }

    function getKaryawan(Request $request)
    {
        try {
            $nip = $request->get('nip');
            $data = KaryawanModel::select('nama_karyawan')->where('nip', $nip)->first()->nama_karyawan ?? 'null';
            return response()->json($data);
        }catch (Exception $e){
            return $e;
        }
    }

    public function autocomplete(Request $request)
    {
        $data = KaryawanModel::select("nama_karyawan", "nip")
                    ->where('nip', 'LIKE', '%'. $request->get('search'). '%')
                    ->get();
        foreach($data as $item ){
            $usersArray[] = array(
                "label" => $item->nip.'-'.$item->nama_karyawan,
                "value" => $item->nip,
                "nama" => $item->nama_karyawan,
            );
        }

        return response()->json($usersArray);
    }
}
