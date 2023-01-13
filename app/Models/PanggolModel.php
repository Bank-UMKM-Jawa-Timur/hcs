<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanggolModel extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'mst_pangkat_golongan';
    protected $keyType = 'string';
}
