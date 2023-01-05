<?php

namespace App\Http\Controllers\Api\Select2;

use App\Http\Controllers\Controller;
use App\Http\Resources\select2\KaryawanResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->q;
        $karyawan = DB::table('mst_karyawan')
            ->join('mst_jabatan', 'mst_jabatan.kd_jabatan', '=', 'mst_karyawan.kd_jabatan')
            ->orWhere('nip', 'LIKE', "%{$query}%")
            ->orWhere('nama_karyawan', 'LIKE', "%{$query}%")
            ->get();

        return KaryawanResource::collection($karyawan);
    }
}
