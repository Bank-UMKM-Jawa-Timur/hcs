<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagianModel extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'mst_bagian';
    protected $keyType = 'string';
}
