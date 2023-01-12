<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabangModel extends Model
{
    use HasFactory;

    protected $table = 'mst_cabang';
    protected $primaryKey = 'kd_cabang';
}
