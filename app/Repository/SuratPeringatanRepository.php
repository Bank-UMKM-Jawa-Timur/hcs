<?php

namespace App\Repository;

use App\Models\SpModel;
use Carbon\Carbon;

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

        if (isset($data['first_date'])) {
            $firstDate = Carbon::parse($data['first_date']);
            $endDate = Carbon::parse($data['end_date']);

            if (!$endDate->gt($firstDate)) return $sp->get();

            $sp->whereBetween('tanggal_sp', [$data['first_date'], $data['end_date']]);
        }

        return $sp->get();
    }
}
