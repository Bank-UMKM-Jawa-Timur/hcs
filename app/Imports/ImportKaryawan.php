<?php

namespace App\Imports;

use App\Models\KaryawanModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportKaryawan implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model()
    {
        // return new KaryawanModel([
        //     'nip' => $row['nip'],
        //     'nama_karyawan' => $row['nama_karyawan'],
        //     'nik' => $row['nik'],
        //     'ket_jabatan' => $row['ket_jabatan'], 
        //     'kd_subdivisi' => $row['kd_subdiv'],
        //     'id_cabang' => $row['id_cabang'],
        //     'kd_jabatan' => $row['kd_jabatan'],
        //     'kd_panggol' => $row['kd_pangkat_golongan'],
        //     'id_is' => $row['id_is'],
        //     'kd_agama' => $row['agama'],
        //     'tmp_lahir' => $row['tmp_lahir'],
        //     'tgl_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_lahir']),
        //     'kewarganegaraan' => $row['kewarganegaraan'],
        //     'jk' => $row['jenis_kelamin'],
        //     'status' => $row['status_pernikahan'],
        //     'alamat_ktp' => $row['alamat_ktp'],
        //     'alamat_sek' => $row['alamat_sekarang'],
        //     'kpj' => $row['kpj'],
        //     'jkn' => $row['jkn'],
        //     'gj_pokok' => $row['gj_pokok'],
        //     'gj_penyesuaian' => $row['gj_penyesuaian'],
        //     'status_karyawan' => $row['status_karyawan'],
        //     'skangkat' => $row['skangkat'],
        //     'tanggal_pengangkat' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_pengangkat']),
        //     'created_at' => now(),
        // ]);
    }

    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            $id_is = null;

            if($row['status_pernikahan'] == 'Kawin'){
                DB::table('is')->insert([
                    'enum' => $row['pasangan'],
                    'is_nama' => $row['nama_pasangan'],
                    'is_tgl_lahir' => $row['tgl_lahir_pasangan'],
                    'is_alamat' => $row['alamat_pasangan'],
                    'is_pekerjaan' => $row['pekerjaan_pasangan'],
                    'is_jml_anak' => $row['jumlah_anak'],
                    'created_at' => now()
                ]);

                $id_is = DB::table('is')
                    ->orderBy('id', 'DESC')
                    ->first();

                $id_is = $id_is->id;
            }

            DB::table('mst_karyawan')
                ->insert([
                    'nip' => $row['nip'],
                    'nama_karyawan' => $row['nama_karyawan'],
                    'nik' => $row['nik'],
                    'ket_jabatan' => $row['ket_jabatan'], 
                    'kd_entitas' => $row['kd_kantor'],
                    'kd_jabatan' => $row['kd_jabatan'],
                    'kd_bagian' => $row['kd_bagian'],
                    'kd_panggol' => $row['kd_pangkat_golongan'],
                    'id_is' => $id_is,
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
}
