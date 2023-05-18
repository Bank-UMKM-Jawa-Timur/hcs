<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengkinianDataImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $arrayDataKeluarga = array();
        foreach($collection as $item){
            $karyawan = DB::table('mst_karyawan')
                ->where('nip', $item['nip'])
                ->first();
            
            $nip = $item['nip'];

            $tunjangan = DB::table('tunjangan_karyawan')
                ->where('nip', $item['nip'])
                ->get();
            $isua = DB::table('keluarga')
                ->where('nip', $item['nip'])
                ->whereIn('enum', ['Istri', 'Suami'])
                ->first();
            $anak = DB::table('keluarga')
                ->where('nip', $item['nip'])
                ->whereIn('enum', ['ANAK1', 'ANAK2'])
                ->get();
            // dd($isua);

            if($isua != null && $item['nama_isua'] != null){
                $idIsua = $isua->id;
                DB::table('keluarga')
                    ->where('id', $idIsua)
                    ->update([
                        'nama' => $item['nama_isua'],
                        'tgl_lahir' => Date::excelToDateTimeObject($item['tgl_lahir_isua']),
                        'alamat' => $item['alamat_isua'],
                        'pekerjaan' => $item['pekerjaan_isua'],
                        'jml_anak' => $item['jml_anak'],
                        'nip' => $item['nip'],
                        'sk_tunjangan' => $item['sk_tunjangan_isua']
                    ]);

                DB::table('history_pengkinian_data_keluarga')
                    ->insert([
                        'enum' => $isua->enum,
                        'nama' => $isua->nama,
                        'tgl_lahir' => $isua->tgl_lahir,
                        'alamat' => $isua->alamat,
                        'pekerjaan' => $isua->pekerjaan,
                        'jml_anak' => $isua->jml_anak,
                        'sk_tunjangan' => $isua->sk_tunjangan,
                        'nip' => $isua->nip
                    ]);
            } else if($item['nama_isua']){
                DB::table('keluarga')
                    ->insert([
                        'enum' => $item['keterangan'],
                        'nama' => $item['nama_isua'],
                        'tgl_lahir' => $item['tgl_lahir_isua'],
                        'alamat' => $item['alamat_isua'],
                        'pekerjaan' => $item['pekerjaan_isua'],
                        'jml_anak' => $item['jml_anak'],
                        'nip' => $item['nip'],
                        'sk_tunjangan' => $item['sk_tunjangan_isua']
                    ]);
            } if(count($anak) > 0){
                if(count($anak) == 1){
                    DB::table('history_pengkinian_data_keluarga')
                        ->insert($anak[0]);
                    DB::table('keluarga')
                        ->where('id', $anak[0]->id)
                        ->update([
                            'nama' => $item['nama_anak_pertama'],
                            'tgl_lahir' => $item['tgl_lahir_anak_pertama'],
                            'sk_tunjangan' => $item['sk_tunjangan_anak_pertama']
                        ]);
                } else{
                    foreach($anak as $i => $ank){
                        DB::table('history_pengkinian_data_keluarga')
                            ->insert([
                                'nip' => $item['nip'],
                                'enum' => $ank->enum,
                                'tgl_lahir' => $ank->tgl_lahir,
                                'sk_tunjangan' => $ank->sk_tunjangan
                            ]);

                        if($ank->enum == 'ANAK1'){
                            DB::table('keluarga')
                                ->where('id', $ank->id)
                                ->update([
                                    'nama' => $item['nama_anak_pertama'],
                                    'tgl_lahir' => Date::excelToDateTimeObject($item['tgl_lahir_anak_pertama']),
                                    'sk_tunjangan' => $item['sk_tunjangan_anak_pertama']
                                ]);
                        } else{
                            DB::table('keluarga')
                                ->where('id', $ank->id)
                                ->update([
                                    'nama' => $item['nama_anak_kedua'],
                                    'tgl_lahir' => Date::excelToDateTimeObject($item['tgl_lahir_anak_kedua']),
                                    'sk_tunjangan' => $item['sk_tunjangan_anak_kedua']
                                ]);
                        }
                    }
                }
            }
                
            DB::table('history_pengkinian_data_karyawan')
                ->insert([
                    'nip' => $karyawan->nip,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'nik' => $karyawan->nik,
                    'ket_jabatan' => $karyawan->ket_jabatan,
                    'kd_entitas' => $karyawan->kd_entitas,
                    'kd_bagian' => $karyawan->kd_bagian,
                    'kd_jabatan' => $karyawan->kd_jabatan,
                    'kd_panggol' => $karyawan->kd_panggol,
                    'kd_agama' => $karyawan->kd_agama,
                    'tmp_lahir' => $karyawan->tmp_lahir,
                    'tgl_lahir' => $karyawan->tgl_lahir,
                    'kewarganegaraan' => $karyawan->kewarganegaraan,
                    'jk' => $karyawan->jk,
                    'status' => $karyawan->status,
                    'alamat_ktp' => $karyawan->alamat_ktp,
                    'alamat_sek' => $karyawan->alamat_sek,
                    'kpj' => $karyawan->kpj,
                    'jkn' => $karyawan->jkn,
                    'gj_pokok' => $karyawan->gj_pokok,
                    'gj_penyesuaian' => $karyawan->gj_penyesuaian,
                    'status_karyawan' => $karyawan->status_karyawan,
                    'status_jabatan' => $karyawan->status_jabatan,
                    'skangkat' => $karyawan->skangkat,
                    'tanggal_pengangkat' => $karyawan->tanggal_pengangkat,
                    'no_rekening' => $karyawan->no_rekening,
                    'created_at' => now(),
                ]);
            DB::table('mst_karyawan')
                ->where('nip', $item['nip'])
                ->update([
                    'nama_karyawan' => $item['nama_karyawan'],
                    'nik' => $item['nik'],
                    'ket_jabatan' => $item['ket_jabatan'],
                    'kd_entitas' => $item['kd_entitas'],
                    'kd_bagian' => $item['bagian'],
                    'kd_jabatan' => $item['jabatan'],
                    'kd_panggol' => $item['panggol'],
                    'kd_agama' => $item['agama'],
                    'tmp_lahir' => $item['tmp_lahir'],
                    'tgl_lahir' => Date::excelToDateTimeObject($item['tgl_lahir']),
                    'kewarganegaraan' => $item['kewarganegaraan'],
                    'jk' => $item['jenis_kelamin'],
                    'status' => $item['status_pernikahan'],
                    'alamat_ktp' => $item['alamat_ktp'],
                    'alamat_sek' => $item['alamat_sekarang'],
                    'kpj' => $item['kpj'],
                    'jkn' => $item['jkn'],
                    'gj_pokok' => $item['gj_pokok'],
                    'gj_penyesuaian' => $item['gj_penyesuaian'],
                    'status_karyawan' => $item['status_karyawan'],
                    'status_jabatan' => $item['status_jabatan'],
                    'skangkat' => $item['skangkat'],
                    'tanggal_pengangkat' => Date::excelToDateTimeObject($item['tanggal_pengangkat']),
                    'no_rekening' => $item['no_rek'],
                    'updated_at' => now(),
                ]);
            if(count($tunjangan) > 0){
                foreach($tunjangan as $tj){
                    if($item['tj_keluarga'] != 0 && $tj->id_tunjangan == 1){
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $item['nip'],
                                'id_tunjangan' => $tj->id_tunjangan,
                                'nominal' => $tj->nominal,
                            ]);
                        DB::table('tunjangan_karyawan')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', 1)
                            ->update([
                                'nominal' => $item['tj_keluarga']
                            ]);
                    }
                    if($item['tj_telairlis'] != 0 && $tj->id_tunjangan == 2){
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $item['nip'],
                                'id_tunjangan' => $tj->id_tunjangan,
                                'nominal' => $tj->nominal,
                            ]);
                        DB::table('tunjangan_karyawan')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', 2)
                            ->update([
                                'nominal' => $item['tj_telairlis']
                            ]);
                    }
                    if($item['tj_jabatan'] != 0 && $tj->id_tunjangan == 3){
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $item['nip'],
                                'id_tunjangan' => $tj->id_tunjangan,
                                'nominal' => $tj->nominal,
                            ]);
                        DB::table('tunjangan_karyawan')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', 3)
                            ->update([
                                'nominal' => $item['tj_jabatan']
                            ]);
                    }
                    if($item['tj_teller'] != 0 && $tj->id_tunjangan == 4){
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $item['nip'],
                                'id_tunjangan' => $tj->id_tunjangan,
                                'nominal' => $tj->nominal,
                            ]);
                        DB::table('tunjangan_karyawan')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', 4)
                            ->update([
                                'nominal' => $item['tj_teller']
                            ]);
                    }
                    if($item['tj_perumahan'] != 0 && $tj->id_tunjangan == 5){
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $item['nip'],
                                'id_tunjangan' => $tj->id_tunjangan,
                                'nominal' => $tj->nominal,
                            ]);
                        DB::table('tunjangan_karyawan')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', 5)
                            ->update([
                                'nominal' => $item['tj_perumahan']
                            ]);
                    }
                    if($item['tj_kemahalan'] != 0 && $tj->id_tunjangan == 6){
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $item['nip'],
                                'id_tunjangan' => $tj->id_tunjangan,
                                'nominal' => $tj->nominal,
                            ]);
                        DB::table('tunjangan_karyawan')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', 6)
                            ->update([
                                'nominal' => $item['tj_kemahalan']
                            ]);
                    }
                    if($item['tj_pelaksana'] != 0 && $tj->id_tunjangan == 7){
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $item['nip'],
                                'id_tunjangan' => $tj->id_tunjangan,
                                'nominal' => $tj->nominal,
                            ]);
                        DB::table('tunjangan_karyawan')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', 7)
                            ->update([
                                'nominal' => $item['tj_pelaksana']
                            ]);
                    }
                    if($item['tj_kesejahteraan'] != 0 && $tj->id_tunjangan == 8){
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $item['nip'],
                                'id_tunjangan' => $tj->id_tunjangan,
                                'nominal' => $tj->nominal,
                            ]);
                        DB::table('tunjangan_karyawan')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', 8)
                            ->update([
                                'nominal' => $item['tj_kesejahteraan']
                            ]);
                    }
                    if($item['dpp'] != 0 && $tj->id_tunjangan == 15){
                        DB::table('history_pengkinian_tunjangan_karyawan')
                            ->insert([
                                'nip' => $item['nip'],
                                'id_tunjangan' => $tj->id_tunjangan,
                                'nominal' => $tj->nominal,
                            ]);
                        DB::table('tunjangan_karyawan')
                            ->where('nip', $nip)
                            ->where('id_tunjangan', 15)
                            ->update([
                                'nominal' => $item['dpp']
                            ]);

                    }
                }
            } else {
                if($item['tj_keluarga'] != 0){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $item['nip'],
                            'id_tunjangan' => 1,
                            'nominal' => $item['tj_keluarga']
                        ]);
                }
                if($item['tj_telairlis'] != 0){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $item['nip'],
                            'id_tunjangan' => 2,
                            'nominal' => $item['tj_telairlis']
                        ]);
                }
                if($item['tj_jabatan'] != 0){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $item['nip'],
                            'id_tunjangan' => 3,
                            'nominal' => $item['tj_jabatan']
                        ]);
                }
                if($item['tj_teller'] != 0){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $item['nip'],
                            'id_tunjangan' => 4,
                            'nominal' => $item['tj_teller']
                        ]);
                }
                if($item['tj_perumahan'] != 0){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $item['nip'],
                            'id_tunjangan' => 5,
                            'nominal' => $item['tj_perumahan']
                        ]);
                }
                if($item['tj_kemahalan'] != 0){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $item['nip'],
                            'id_tunjangan' => 6,
                            'nominal' => $item['tj_kemahalan']
                        ]);
                }
                if($item['tj_pelaksana'] != 0){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $item['nip'],
                            'id_tunjangan' => 7,
                            'nominal' => $item['tj_pelaksana']
                        ]);
                }
                if($item['tj_kesejahteraan'] != 0){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $item['nip'],
                            'id_tunjangan' => 8,
                            'nominal' => $item['tj_kesejahteraan']
                        ]);
                }
                if($item['dpp'] != 0){
                    DB::table('tunjangan_karyawan')
                        ->insert([
                            'nip' => $item['nip'],
                            'id_tunjangan' => 15,
                            'nominal' => $item['dpp']
                        ]);
                }
            }
        }
    }
}
