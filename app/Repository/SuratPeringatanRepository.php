<?php

namespace App\Repository;

use App\Models\SpModel;

class SuratPeringatanRepository
{
    public function store(array $data)
    {
        return SpModel::create([
            'nip' => $data['nip'],
            'tanggal_sp' => $data['tanggal_sp'],
            'no_sp' => $data['no_sp'],
            'pelanggaran' => $data['pelanggaran'],
            'sanksi' => $data['sanksi']
        ]);
    }
}
