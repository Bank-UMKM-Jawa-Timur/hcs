<?php

namespace App\Repository;

use App\Models\SpModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuratPeringatanRepository
{
    public function store(array $data)
    {
        $filename = null;
        if($data['file_sk'] != null){
            $file = $data['file_sk'];
            $idSP = DB::table('surat_peringatan')->orderBy('id', 'desc')->first();
            $id = intval($idSP->id) + 1;
            $folderPath = public_path() . '/upload/sp/' . $id;
            $filename = date('YmdHis').'.'. $file->getClientOriginalExtension();
            $path = realpath($folderPath);
    
            if(!($path !== true AND is_dir($path))){
                mkdir($folderPath, 0755, true);
            }
            $file->move($folderPath, $filename);
        }

        return SpModel::create([
            'nip' => $data['nip'],
            'tanggal_sp' => $data['tanggal_sp'],
            'no_sp' => $data['no_sp'],
            'pelanggaran' => $data['pelanggaran'],
            'sanksi' => $data['sanksi'],
            'file_sk' => $filename
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
