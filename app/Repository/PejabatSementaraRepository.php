<?php

namespace App\Repository;

use App\Models\PjsModel;
use Illuminate\Http\UploadedFile;

class PejabatSementaraRepository
{
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
}
