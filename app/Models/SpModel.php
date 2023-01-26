<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpModel extends Model
{
    use HasFactory;

    protected $table = 'surat_peringatan';

    protected $fillable = [
        'nip', 'tanggal_sp', 'no_sp', 'pelanggaran', 'sanksi',
    ];

    protected $casts = [
        'tanggal_sp' => 'date',
    ];

    public function karyawan()
    {
        return $this->belongsTo(KaryawanModel::class, 'nip');
    }
}
