<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkpModel extends Model
{
    use HasFactory;
    protected $table = 'set_ptkp';
    protected $guarded  = 'id';
    protected $fillable  = ['kode', 'ptkp_tahun', 'ptkp_bulan', 'keterangan'];
}
