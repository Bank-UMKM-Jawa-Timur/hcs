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
            $request->get('nip');
            $explode_data = collect(json_decode($request->get('nip'), true));
            $explode_id = $explode_data->pluck('nip')->toArray();

            $data = KaryawanModel::select('nip', 'nama_karyawan')
                ->whereIn('nip', $explode_id)
                ->get();

            $response = $explode_data->map(function ($item) use ($data) {
                $nip = $item['nip'];
                $row = $item['row'];

                // Check if the NIP is found in the server-side data
                $found = $data->where('nip', $nip)->first();

                return [
                    'row' => $row,
                    'nip' => $found ? $found->nip : $nip,
                    'cek' => $found ? null : '-',
                    'nama_karyawan' => $found ? $found->nama_karyawan : 'Karyawan Tidak Ditemukan',
                ];
            })->toArray();

            return response()->json($response);
        }catch (Exception $e){
            return $e;
        }
    }

    function getKaryawan2(Request $request)
    {
        ini_set('max_input_vars','2000');
        try {
            $request->get('nip');
            $explode_data = collect(json_decode($request->get('nip'), true));
            $explode_id = $explode_data->pluck('nip')->toArray();

            if (auth()->user()->kd_cabang != null) {
                $data = KaryawanModel::select('nama_karyawan', 'nip', 'no_rekening','kd_entitas')
                    ->whereIn('nip', $explode_id)
                    ->where('kd_entitas', auth()->user()->kd_cabang)
                    ->whereNull('tanggal_penonaktifan')
                    ->get();
            }else{
                $data = KaryawanModel::select('nama_karyawan', 'nip', 'no_rekening','kd_entitas')
                    ->whereIn('nip', $explode_id)
                    ->whereNull('tanggal_penonaktifan')
                    ->get();
            }

            $response = $explode_data->map(function ($item) use ($data) {
                $nip = $item['nip'];
                $row = $item['row'];

                // Check if the NIP is found in the server-side data
                $found = $data->where('nip', $nip)->first();

                return [
                    'row' => $row,
                    'nip' => $found ? $found->nip : $nip,
                    'cek' => $found ? null : '-',
                    'kd_entitas' => $found->kd_entitas ?? '000',
                    'nama_karyawan' => $found ? $found->nama_karyawan : 'Karyawan Tidak Ditemukan',
                    'no_rekening' => $found ? $found->no_rekening : 'No Rek Tidak Ditemukan',
                ];
            })->toArray();

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
