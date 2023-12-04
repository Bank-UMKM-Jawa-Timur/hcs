<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\KaryawanResource;
use App\Models\KaryawanModel;
use Carbon\Carbon;
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
            $explode_id = json_decode($request->get('nip'), true);
            // $nip = $request->get('nip');
            // return gettype($explode_id);
            $data = KaryawanModel::select('nip','nama_karyawan')->whereIn('nip', $explode_id)->get();
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

    public function getTHR(Request $request) {
        $karyawan = KaryawanModel::where('nip', $request->get('nip'))
          ->first();
        $dateStart = Carbon::parse($karyawan->tgl_mulai);
        $dateNow = Carbon::now();
        $monthDiff = $dateNow->diffInMonths($dateStart);

        if($monthDiff < 12) {
          $thr = $karyawan->gj_pokok * 2 / $monthDiff;
        } else{
          $thr = $karyawan->gj_pokok * 2;
        }

        return response()->json([
            'karyawan' => $karyawan->nama_karyawan,
            'thr' => $thr
        ]);
    }
}
