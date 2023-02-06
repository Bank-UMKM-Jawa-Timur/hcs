<?php

namespace App\Models;

use App\Service\EntityService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanModel extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'mst_jabatan';
    protected $keyType = 'string';
}
