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
        ini_set('max_input_vars','2000');
        try {
            $explode_data = json_decode($request->get('nip'), true);
            $explode_id = array_column($explode_data, 'nip');

            $data = KaryawanModel::select('nip', 'nama_karyawan')->whereIn('nip', $explode_id)->get();

            $response = [];
            foreach ($explode_data as $key => $item) {
                $nip = $item['nip'];
                $row = $item['row'];

                // Check if the NIP is found in the server-side data
                $found = $data->where('nip', $nip)->first();

                if ($found) {
                    $response[] = [
                        'row' => $row,
                        'nip' => $found->nip,
                        'cek' => null,
                        'nama_karyawan' => $found->nama_karyawan,
                    ];
                } else {
                    // If NIP not found, return an error response
                    $response[] = [
                        'row' => $row,
                        'nip' => $item['nip'],
                        'cek' => '-',
                        'nama_karyawan' => 'Karyawan Tidak Ditemukan',
                    ];
                }
            }

            return response()->json($response);
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
