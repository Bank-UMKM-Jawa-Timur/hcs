<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportUpdateKaryawan implements ToCollection, WithHeadingRow
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $key => $item){
            $nip = $item['nip'];
            $norek = $item['norek'];
            $npwp = $item['npwp'] ? str_replace(['.', '-', ' '], '', $item['npwp']) : null;
            $ptkp = $item['ptkp'];
            $pendidikan = $item['pendidikan'];
            $jurusan = $item['jurusan'];
            $alamat_ktp = $item['alamat_ktp'];
            $alamat_domisili = $item['alamat_domisili'];

            DB::table('mst_karyawan')
                ->where('nip', $nip)
                ->update([
                    'no_rekening' => $norek,
                    'npwp' => $npwp,
                    'status_ptkp' => $ptkp,
                    'pendidikan' => $pendidikan,
                    'pendidikan_major' => $jurusan,
                    'alamat_ktp' => $alamat_ktp,
                    'alamat_sek' => $alamat_domisili,
                    'updated_at' => now(),
                ]);
        }
    }
}
