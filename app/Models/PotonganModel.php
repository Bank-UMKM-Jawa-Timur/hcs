<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganModel extends Model
{
    use HasFactory;

    protected $table = 'potongan_gaji';

    protected $fillable = [
        'nip',
        'kredit_koperasi',
        'iuran_koperasi',
        'kredit_pegawai',
        'iuran_ik',
    ];
}
