<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiPerBulanModel extends Model
{
    use HasFactory;

    protected $table = 'gaji_per_bulan';
    protected $fillable = [
        'nip',
        'bulan', 
        'tahun', 
        'gj_pokok',
        'gj_penyesuaian',
        'tj_keluarga',
        'tj_telepon',
        'tj_jabatan',
        'tj_teller',
        'tj_perumahan',
        'tj_kemahalan',
        'tj_pelaksanaan',
        'tj_kesejahteraan',
        'tj_multilevel',
        'tj_ti', 
        'tj_transport',
        'tj_pulsa',
        'tj_vitamin',
        'uang_makan',
        'dpp',
        'created_at'
    ];
}
