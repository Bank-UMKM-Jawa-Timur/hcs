<?php

namespace App\Http\Controllers\LaporanPergerakanKarir;

use App\Http\Controllers\Controller;
use App\Service\EntityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanMutasiController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('laporan - laporan pergerakan karir - laporan mutasi')) {
            return view('roles.forbidden');
        }
        $data = null;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        if ($start_date && $end_date) {
            try {
                $data = DB::table('demosi_promosi_pangkat')
                    ->where('keterangan', 'Mutasi')
                    ->select(
                        'demosi_promosi_pangkat.*',
                        'karyawan.*',
                        'newPos.nama_jabatan as jabatan_baru',
                        'oldPos.nama_jabatan as jabatan_lama'
                    )
                    ->join('mst_karyawan as karyawan', 'karyawan.nip', '=', 'demosi_promosi_pangkat.nip')
                    ->join('mst_jabatan as newPos', 'newPos.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_baru')
                    ->join('mst_jabatan as oldPos', 'oldPos.kd_jabatan', '=', 'demosi_promosi_pangkat.kd_jabatan_lama')
                    ->whereBetween('demosi_promosi_pangkat.tanggal_pengesahan', [$start_date, $end_date])
                    ->orderBy('id', 'desc')
                    ->get();

                $data->map(function ($mutasi) {
                    $entity = EntityService::getEntity($mutasi->kd_entitas_baru);
                    $type = $entity->type;
                    $mutasi->kantor_baru = '-';

                    if ($type == 2)
                        $mutasi->kantor_baru = "Cab. " . $entity->cab->nama_cabang;
                    if ($type == 1) {
                        if (isset($entity->subDiv)) {
                            $mutasi->kantor_baru = $entity?->subDiv?->nama_subdivisi . " (Pusat)";
                        } else if (isset($entity->div)) {
                            $mutasi->kantor_baru = $entity?->div?->nama_divisi . " (Pusat)";
                        }
                    }

                    return $mutasi;
                });

                $data->map(function ($mutasiLama) {
                    $entityLama = EntityService::getEntity($mutasiLama->kd_entitas_lama);
                    $typeLama = $entityLama->type;
                    $mutasiLama->kantor_lama = '-';

                    if ($typeLama == 2)
                        $mutasiLama->kantor_lama = "Cab. " . $entityLama->cab->nama_cabang;
                    if ($typeLama == 1) {
                        if (isset($entityLama->subDiv)) {
                            $mutasiLama->kantor_lama = $entityLama->subDiv->nama_subdivisi . " (Pusat)";
                        } else if (isset($entityLama->div)) {
                            $mutasiLama->kantor_lama = $entityLama->div->nama_divisi . " (Pusat)";
                        }
                    }

                    return $mutasiLama;
                });
            } catch (\Exception $e) {
                return $e->getMessage();
                return back()->withError('Terjadi kesalahan');
            } catch (\Illuminate\Database\QueryException $e) {
                return $e->getMessage();
                return back()->withError('Terjadi kesalahan pada database');
            }
        }
        return view('laporan_pergerakan_karir.mutasi.index', compact('data'));
    }
}
