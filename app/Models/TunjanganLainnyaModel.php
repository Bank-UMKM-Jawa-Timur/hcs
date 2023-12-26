<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TunjanganLainnyaModel extends Model
{
    use HasFactory;
    protected $table = 'tunjangan_lainnya';

    public function mstTunjangan() {
        return $this->hasOne(TunjanganModel::class, 'id');
    }
}
