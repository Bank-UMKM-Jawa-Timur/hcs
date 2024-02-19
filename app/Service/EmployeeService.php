<?php

namespace App\Service;

use App\Models\KaryawanModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeService
{
    public static function deactivate(array $data, UploadedFile $sk)
    {
        // Store the SK
        $skName = 'sk_' . time() . '.' . $sk->getClientOriginalExtension();
        $sk->storeAs('sk_pemberhentian', $skName, ['disk' => 'public']);

        // Update status karyawan
        return KaryawanModel::find($data['nip'])
            ->update([
                'status_karyawan' => 'Nonaktif',
                'tanggal_penonaktifan' => $data['tanggal_penonaktifan'],
                'kategori_penonaktifan' => $data['kategori_penonaktifan'],
                'sk_pemberhentian' => $skName,
                'is_proses_gaji' => (int) $data['ikut_penggajian'] == 1,
            ]);
    }
}
