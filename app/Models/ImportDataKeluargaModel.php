<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportDataKeluargaModel extends Model
{
    use HasFactory;

    protected $table = 'keluarga';

    protected $fillable = [
        'id',
        'nama',
        'tgl_lahir',
        'pekerjaan',
        'jml_anak',
        'created_at',
        'nip',
        'sk_tunjangan'
    ];
}
