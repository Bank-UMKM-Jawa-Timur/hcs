<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPotonganGaji implements ToCollection, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $item){
            DB::table('potongan_gaji')
                ->insert([
                    'bulan' => $item['bulan'],
                    'tahun' => $item['tahun'],
                    'nip' => $item['nip'],
                    'kredit_koperasi' => $item['kredit_koperasi'],
                    'iuran_koperasi' => $item['iuran_koperasi'],
                    'kredit_pegawai' => $item['kredit_pegawai'],
                    'iuran_jk' => $item['iuran_jk'],
                    'created_at' => now()
                ]);
        }
    }
}
