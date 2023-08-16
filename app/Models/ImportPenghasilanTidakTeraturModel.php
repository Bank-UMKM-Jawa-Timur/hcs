<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportPenghasilanTidakTeraturModel extends Model
{
    use HasFactory;
    
    protected $table = 'penghasilan_tidak_teratur';
    protected $fillable = [
        'nip',
        'id_tunjangan',
        'nominal',
        'tahun',
        'bulan',
        'created_at'
    ];
}
