<?php

namespace App\Imports;

use App\Models\KaryawanModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportKaryawan implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KaryawanModel([
            'nip' => $row['nip'],
            'nama_karyawan' => $row['nama_karyawan'],
            'nik' => $row['nik'],
            'ket_jabatan' => $row['ket_jabatan'], 
            'id_subdivisi' => $row['id_subdivisi'],
            'id_cabang' => $row['id_cabang'],
            'id_jabatan' => $row['id_jabatan'],
            'kd_panggol' => $row['kd_pangkat_golongan'],
            'id_is' => $row['id_is'],
            'kd_agama' => $row['agama'],
            'tmp_lahir' => $row['tmp_lahir'],
            'tgl_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_lahir']),
            'kewarganegaraan' => $row['kewarganegaraan'],
            'jk' => $row['jenis_kelamin'],
            'status' => $row['status_pernikahan'],
            'alamat_ktp' => $row['alamat_ktp'],
            'alamat_sek' => $row['alamat_sekarang'],
            'kpj' => $row['kpj'],
            'jkn' => $row['jkn'],
            'gj_pokok' => $row['gj_pokok'],
            'gj_penyesuaian' => $row['gj_penyesuaian'],
            'status_karyawan' => $row['status_karyawan'],
            'skangkat' => $row['skangkat'],
            'tanggal_pengangkat' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_pengangkat']),
            'created_at' => now(),
        ]);
    }
}
