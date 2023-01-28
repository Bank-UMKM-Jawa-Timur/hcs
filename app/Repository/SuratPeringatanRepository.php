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

    public function report(array $data)
    {
        $sp = SpModel::with('karyawan');

        if (isset($data['tahun'])) $sp->whereYear('tanggal_sp', $data['tahun']);
        if (isset($data['nip'])) $sp->where('nip', $data['nip']);

        return $sp->get();
    }
}
