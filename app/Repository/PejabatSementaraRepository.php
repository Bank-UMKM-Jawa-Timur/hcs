<?php

namespace App\Repository;

use App\Models\PjsModel;
use Illuminate\Http\UploadedFile;

class PejabatSementaraRepository
{
    public function getAllPejabatSementara($search, $limit=10, $page=1) {
        return $this->getDataPejabatSementara($search, $limit, $page);
    }

    public function getDataPejabatSementara($search, $limit=10, $page=1) {
        $pjs = PjsModel::select(
            'pejabat_sementara.id',
            'pejabat_sementara.nip',
            'pejabat_sementara.tanggal_mulai',
            'pejabat_sementara.tanggal_berakhir',
            'mst_karyawan.nama_karyawan',
            'mst_karyawan.kd_entitas',
            'mst_jabatan.kd_jabatan',
            'mst_jabatan.nama_jabatan',
        )
        ->join('mst_karyawan','pejabat_sementara.nip','=','mst_karyawan.nip')
        ->join('mst_jabatan','pejabat_sementara.kd_jabatan','=','mst_jabatan.kd_jabatan')
        // ->with('karyawan')
        // ->with('jabatan')
        ->when($search, function ($query) use ($search) {
            $query->where('pejabat_sementara.nip', 'like', "%$search%")
            ->orWhere('pejabat_sementara.tanggal_mulai', 'like', "%$search%")
            ->orWhere('pejabat_sementara.tanggal_berakhir', 'like', "%$search%")
            ->orWhere('mst_karyawan.nama_karyawan', 'like', "%$search%")
            ->orWhere('mst_jabatan.nama_jabatan', 'like', "%$search%");
        })
        ->paginate($limit);

        return $pjs;
    }
    
    public function store(array $data, UploadedFile|null $sk)
    {
        $skName = null;

        // Store the SK
        if (!is_null($sk)) {
            $skName = 'sk_' . time() . '.' . $sk->getClientOriginalExtension();
            $sk->storeAs('sk_pejabat_sementara', $skName, ['disk' => 'public']);
        }

        return PjsModel::create([
            'nip' => $data['nip'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_berakhir' => null,
            'kd_jabatan' => $data['kd_jabatan'],
            'kd_entitas' => $data['kd_entitas'],
            'kd_bagian' => isset($data['kd_bagian']) ? $data['kd_bagian'] : null,
            'no_sk' => $data['no_sk'],
            'file_sk' => $skName,
        ]);
    }

    public function deactivate(PjsModel $pjs, $endDate = null)
    {
        if (!$endDate) $endDate = now();

        return $pjs->update([
            'tanggal_berakhir' => $endDate,
        ]);
    }
}
