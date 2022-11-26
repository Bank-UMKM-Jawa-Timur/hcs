<?php

namespace App\Imports;

use App\Models\KaryawanModel;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportKaryawan implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KaryawanModel([
            'nip' => $row[0],
            
        ]);
    }
}
