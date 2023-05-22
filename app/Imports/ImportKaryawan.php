<?php

namespace App\Imports;

use App\Models\KaryawanModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Throwable;

class ImportKaryawan implements ToCollection, WithHeadingRow, SkipsOnError, WithValidation, SkipsEmptyRows
{
    use Importable, SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        // dd($rows);
        foreach($rows as $row){
            $id_is = null;
            // dd($row);
            
            $status_pernikahan = $row['status_pernikahan'];
            if($status_pernikahan == null || $status_pernikahan == ''){
                $status_pernikahan = 'Tidak Diketahui';
            }
            // dd($row);
            DB::table('mst_karyawan')
                ->insert([
                    'nip' => $row['nip'],
                    'nama_karyawan' => $row['nama_karyawan'],
                    'nik' => $row['nik'],
                    'ket_jabatan' => $row['ket_jabatan'], 
                    'kd_entitas' => $row['kd_entitas'],
                    'kd_jabatan' => $row['kd_jabatan'],
                    'status_jabatan' => $row['status_jabatan'],
                    'kd_bagian' => $row['kd_bagian'],
                    'kd_panggol' => ($row['kd_pangkat_golongan'] != '') ? $row['kd_pangkat_golongan'] : null,
                    'kd_agama' => $row['agama'],
                    'tmp_lahir' => $row['tmp_lahir'],
                    'tgl_lahir' => ($row['tgl_lahir'] != null) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_lahir']) : null,
                    'kewarganegaraan' => $row['kewarganegaraan'],
                    'jk' => $row['jk'],
                    'status' => $status_pernikahan,
                    'alamat_ktp' => $row['alamat_ktp'],
                    'alamat_sek' => $row['alamat_sekarang'],
                    'kpj' => $row['kpj'],
                    'jkn' => $row['jkn'],
                    'gj_pokok' => $row['gj_pokok'],
                    'gj_penyesuaian' => $row['gj_penyesuaian'],
                    'status_karyawan' => $row['status_karyawan'],
                    'skangkat' => $row['skangkat'],
                    'tanggal_pengangkat' => ($row['tanggal_pengangkat'] != null) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_pengangkat']) : null,
                    'no_rekening' => $row['no_rekening'] ?? null,
                    'npwp' => $row['npwp'] ?? null,
                    'pendidikan' => $row['pendidikan'],
                    'pendidikan_major' => $row['pendidikan_major'],
                    'tgl_mulai' => ($row['tgl_mulai'] != null) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_mulai']) : null,
                    'tanggal_penonaktifan' => ($row['tanggal_penonaktifan'] != null) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_penonaktifan']) : null,
                    'sk_penonaktifan' => $row['sk_penonaktifan'],
                    'created_at' => now(),
                ]);

            if($row['tunjangan_keluarga'] != null){
                $id_tunjangan = DB::table('mst_tunjangan')
                    ->where('nama_tunjangan', 'Keluarga')
                    ->select('id')
                    ->first();

                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => $id_tunjangan->id,
                        'nominal' => $row['tunjangan_keluarga'],
                        'created_at' => now()
                    ]);
            } 
            if($row['tunjangan_telepon_air_listrik'] != null){
                $id_tunjangan = DB::table('mst_tunjangan')
                    ->where('nama_tunjangan', 'Telpon, Air dan Listrik')
                    ->select('id')
                    ->first();

                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => $id_tunjangan->id,
                        'nominal' => $row['tunjangan_telepon_air_listrik'],
                        'created_at' => now()
                    ]);
            } 
            if($row['tunjangan_jabatan'] != null){
                $id_tunjangan = DB::table('mst_tunjangan')
                    ->where('nama_tunjangan', 'Jabatan')
                    ->select('id')
                    ->first();

                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => $id_tunjangan->id,
                        'nominal' => $row['tunjangan_jabatan'],
                        'created_at' => now()
                    ]);
            }
            if($row['tunjangan_teller'] != null){
                $id_tunjangan = DB::table('mst_tunjangan')
                    ->where('nama_tunjangan', 'Teller')
                    ->select('id')
                    ->first();

                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => $id_tunjangan->id,
                        'nominal' => $row['tunjangan_teller'],
                        'created_at' => now()
                    ]);
            } 
            if($row['tunjangan_perumahan'] != null){
                $id_tunjangan = DB::table('mst_tunjangan')
                    ->where('nama_tunjangan', 'Perumahan')
                    ->select('id')
                    ->first();

                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => $id_tunjangan->id,
                        'nominal' => $row['tunjangan_perumahan'],
                        'created_at' => now()
                    ]);
            } 
            if($row['tunjangan_kemahalan'] != null){
                $id_tunjangan = DB::table('mst_tunjangan')
                    ->where('nama_tunjangan', 'Kemahalan')
                    ->select('id')
                    ->first();

                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => $id_tunjangan->id,
                        'nominal' => $row['tunjangan_kemahalan'],
                        'created_at' => now()
                    ]);
            } 
            if($row['tunjangan_pelaksana'] != null){
                $id_tunjangan = DB::table('mst_tunjangan')
                    ->where('nama_tunjangan', 'Pelaksana')
                    ->select('id')
                    ->first();

                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => $id_tunjangan->id,
                        'nominal' => $row['tunjangan_pelaksana'],
                        'created_at' => now()
                    ]);
            }
            if($row['tunjangan_kesejahteraan'] != null){
                $id_tunjangan = DB::table('mst_tunjangan')
                    ->where('nama_tunjangan', 'Kesejahteraan')
                    ->select('id')
                    ->first();

                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => $id_tunjangan->id,
                        'nominal' => $row['tunjangan_kesejahteraan'],
                        'created_at' => now()
                    ]);
            }
            if($row['tunjangan_dpp'] != null){
                $id_tunjangan = DB::table('mst_tunjangan')
                    ->where('nama_tunjangan', 'DPP')
                    ->select('id')
                    ->first();

                DB::table('tunjangan_karyawan')
                    ->insert([
                        'nip' => $row['nip'],
                        'id_tunjangan' => $id_tunjangan->id,
                        'nominal' => $row['tunjangan_dpp'],
                        'created_at' => now()
                    ]);
            }
        }   
    }

    public function onError(Throwable $error)
    {    
    }

    public function rules() : array 
    {
       return[
        'nip' => 'unique:mst_karyawan,nip',
        'nama_karyawan' => 'required'
       ];
    }
}
