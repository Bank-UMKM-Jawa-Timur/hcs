<?php

namespace App\Repository;

use App\Models\ImportPenghasilanTidakTeraturModel;
use App\Models\SpModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenghasilanTidakTeraturRepository
{
    public function store(array $data)
    {
        return ImportPenghasilanTidakTeraturModel::create([
            'nip' => $data['nip'],
            'id_tunjangan' => $data['id_tunjangan'],
            'bulan' => $data['bulan'],
            'tahun' => $data['tahun'],
            'nominal' => str_replace('.', '', $data['nominal']),
            'keterangan' => $data['keterangan'],
            'created_at' => now()
        ]);
    }

    public function storeUangDuka(array $data)
    {
        return ImportPenghasilanTidakTeraturModel::create([
            'nip' => $data['nip'],
            'id_tunjangan' => $data['id_tunjangan'],
            'bulan' => $data['bulan'],
            'tahun' => $data['tahun'],
            'nominal' => str_replace('.', '', $data['nominal']),
            'keterangan' => $data['keterangan'] . " meninggal",
            'created_at' => now()
        ]);
    }
}
