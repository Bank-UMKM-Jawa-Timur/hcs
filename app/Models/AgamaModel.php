<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgamaModel extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'mst_agama';
    protected $primaryKey = 'kd_agama';
    protected $keyType = 'string';
}
