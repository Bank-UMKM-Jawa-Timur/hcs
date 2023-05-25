<?php

namespace App\Imports;

use App\Models\ImportDataKeluargaModel;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportDataKeluarga implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    use Importable;

    public function collection(Collection $collection)
    {
        $array = array();
        foreach($collection as $row){
            array_push($array, [
                'nip' => $row['nip'],
                'enum' => $row['keterangan'],
                'nama' => $row['nama'],
                'tgl_lahir' => ($row['tgl_lahir'] != null) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_lahir']) : null,
                'alamat' => $row['alamat'] ?? null,
                'pekerjaan' => $row['pekerjaan'] ?? null,
                'jml_anak' => $row['jml_anak'] ?? null,
                'sk_tunjangan' => $row['sk_tunjangan'],
                'created_at' => now()
            ]);
        }
        
        DB::beginTransaction();
        try{
            ImportDataKeluargaModel::insert($array);
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            dd($e);
        }
    }
}
