<?php

namespace App\Models;

use App\Service\EntityService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PjsModel extends Model
{
    use HasFactory;

    protected $table = 'pejabat_sementara';

    protected $fillable = [
        'nip',
        'tanggal_mulai',
        'tanggal_berakhir',
        'kd_jabatan',
        'kd_entitas',
        'kd_bagian',
        'no_sk',
        'file_sk',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    public function entitas(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attrs) => EntityService::getEntity($attrs['kd_entitas'])
        );
    }

    public function karyawan()
    {
        return $this->belongsTo(KaryawanModel::class, 'nip', 'nip');
    }

    public function jabatan()
    {
        return $this->belongsTo(JabatanModel::class, 'kd_jabatan', 'kd_jabatan');
    }

    public function bagian()
    {
        return $this->belongsTo(BagianModel::class, 'kd_bagian', 'kd_bagian');
    }
}
