<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPHModel extends Model
{
    use HasFactory;

    protected $table = 'pph_yang_dilunasi';
    protected $fillable = [
        'nip',
        'bulan',
        'tahun',
        'total_pph',
        'tanggal',
        'created_at'
    ];
}
