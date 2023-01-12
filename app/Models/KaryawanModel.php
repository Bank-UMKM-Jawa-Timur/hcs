<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanModel extends Model
{
    use HasFactory;

    protected $table = 'mst_karyawan';
    protected $fillable = [
        'nip',
        'nama_karyawan',
        'nik',
        'ket_jabatan',
        'kd_subdivisi',
        'id_cabang',
        'kd_jabatan',
        'kd_panggol',
        'id_is',
        'kd_agama',
        'tmp_lahir',
        'tgl_lahir',
        'kewarganegaraan',
        'jk',
        'status',
        'alamat_ktp',
        'alamat_sek',
        'kpj',
        'jkn',
        'gj_pokok',
        'gj_penyesuaian',
        'status_karyawan',
        'skangkat',
        'tanggal_pengangkat',

    ];

    public function jabatan()
    {
        return $this->belongsTo(JabatanModel::class, 'kd_jabatan', 'kd_jabatan');
    }

    public function bagian()
    {
        return $this->belongsTo(BagianModel::class, 'kd_bagian', 'kd_bagian');
    }

    public function cabang()
    {
        return $this->belongsTo(CabangModel::class);
    }
}
