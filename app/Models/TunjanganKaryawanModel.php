<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TunjanganKaryawanModel extends Model
{
    use HasFactory;
    protected $table = 'tunjangan_karyawan';

    public function mstTunjangan() {
        return $this->hasOne(TunjanganModel::class, 'id');
    }
}
