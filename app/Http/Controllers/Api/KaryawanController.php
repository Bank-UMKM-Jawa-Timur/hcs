<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\KaryawanResource;
use App\Models\KaryawanModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            $is_cabang = auth()->user()->hasRole('cabang');
            $is_pusat = auth()->user()->hasRole('kepegawaian');

            $kd_cabang = DB::table('mst_cabang')
                        ->select('kd_cabang')
                        ->pluck('kd_cabang')
                        ->toArray();
            if ($is_cabang) {
                $kd_cabang = auth()->user()->kd_cabang;
                $data = KaryawanModel::select(
                            'mst_karyawan.nip',
                            'mst_karyawan.nama_karyawan',
                            'mst_karyawan.no_rekening',
                            'mst_karyawan.kd_entitas',
                        )
                        ->whereIn('nip', $explode_id)
                        ->where('kd_entitas', $kd_cabang)
                        ->whereNull('tanggal_penonaktifan')
                        ->get();
            } else {
                $data = KaryawanModel::select(
                            'mst_karyawan.nip',
                            'mst_karyawan.nama_karyawan',
                            'mst_karyawan.no_rekening',
                            'mst_karyawan.kd_entitas',
                        )
                        ->whereIn('nip', $explode_id)
                        ->whereNull('tanggal_penonaktifan')
                        ->get();
            }

            // if (auth()->user()->kd_cabang != null) {
            //     $data = KaryawanModel::select('nama_karyawan', 'nip', 'no_rekening','kd_entitas')
            //         ->whereIn('nip', $explode_id)
            //         ->where('kd_entitas', auth()->user()->kd_cabang)
            //         ->whereNull('tanggal_penonaktifan')
            //         ->get();
            // }else{
            //     $data = KaryawanModel::select('nama_karyawan', 'nip', 'no_rekening','kd_entitas')
            //         ->whereIn('nip', $explode_id)
            //         ->whereNull('tanggal_penonaktifan')
            //         ->get();
            // }

            $response = $explode_data->map(function ($item) use ($data) {
                $tanggal = Request()->get('tanggal');
                $nip = $item['nip'];
                $row = $item['row'];

                // Check if the NIP is found in the server-side data
                $finalisasi = DB::table('gaji_per_bulan as gaji')
                ->join('batch_gaji_per_bulan as batch', 'gaji.batch_id', 'batch.id')
                ->where('batch.status', 'final')
                ->where('gaji.nip', $nip)
                ->orderByDesc('batch.tanggal_input')
                ->whereNull('batch.deleted_at')
                ->first();

                $found = $data->where('nip', $nip)->first();
                $kd_entitas = $found->kd_entitas ?? '000';
                $cabang = DB::table('mst_cabang')->select('nama_cabang')->where('kd_cabang', $kd_entitas)->first();

                if ($finalisasi) {
                    if ($tanggal <= $finalisasi->tanggal_input) {
                        return [
                            'status' => 1,
                            'message' => 'success',
                            'row' => $row,
                            'nip' => $found ? $found->nip : $nip,
                            'cek' => $found ? null : '-',
                            'kd_entitas' => $found->kd_entitas ?? '000',
                            'nama_karyawan' => $found ? $found->nama_karyawan : 'Karyawan Tidak Ditemukan',
                            'no_rekening' => $found ? $found->no_rekening : 'No Rek Tidak Ditemukan',
                        ];
                    } else {
                        return [
                            'status' => 2,
                            'message' => 'success',
                            'row' => $row,
                            'nip' => $found ? $found->nip : $nip,
                            'cek' => $found ? null : '-',
                            'kd_entitas' => $found->kd_entitas ?? '000',
                            'nama_karyawan' => $found ? $found->nama_karyawan : 'Karyawan Tidak Ditemukan',
                            'no_rekening' => $found ? $found->no_rekening : 'No Rek Tidak Ditemukan',
                        ];
                    }

                } else {
                    return [
                        'status' => 3,
                        'message' => 'success',
                        'row' => $row,
                        'nip' => $found ? $found->nip : $nip,
                        'cek' => $found ? null : '-',
                        'kd_entitas' => $found->kd_entitas ?? '000',
                        'nama_karyawan' => $found ? $found->nama_karyawan : 'Karyawan Tidak Ditemukan',
                        'no_rekening' => $found ? $found->no_rekening : 'No Rek Tidak Ditemukan',
                    ];
                }
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
