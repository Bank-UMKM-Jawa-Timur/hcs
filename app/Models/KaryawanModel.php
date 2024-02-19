<?php

namespace App\Models;

use App\Enum\KategoriPenonaktifan;
use App\Service\EntityService;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class KaryawanModel extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $table = 'mst_karyawan';
    protected $primaryKey = 'nip';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nip',
        'nama_karyawan',
        'nik',
        'ket_jabatan',
        'kd_subdivisi',
        'id_cabang',
        'kd_jabatan',
        'kd_panggol',
        'id_is',
        'kd_agama',
        'tmp_lahir',
        'tgl_lahir',
        'kewarganegaraan',
        'jk',
        'status',
        'alamat_ktp',
        'alamat_sek',
        'kpj',
        'jkn',
        'gj_pokok',
        'gj_penyesuaian',
        'status_karyawan',
        'skangkat',
        'tanggal_pengangkat',
        'tanggal_penonaktifan',
        'kategori_penonaktifan',
        'sk_pemberhentian',
        'is_proses_gaji',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tgl_mulai' => 'date',
        'kategori_penonaktifan' => KategoriPenonaktifan::class,
    ];

    public function entitas(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => EntityService::getEntity($attributes['kd_entitas'])
        );
    }

    public function agama()
    {
        return $this->belongsTo(AgamaModel::class, 'kd_agama', 'kd_agama');
    }

    public function jabatan()
    {
        return $this->belongsTo(JabatanModel::class, 'kd_jabatan', 'kd_jabatan');
    }

    public function bagian()
    {
        return $this->belongsTo(BagianModel::class, 'kd_bagian', 'kd_bagian');
    }

    public function cabang()
    {
        return $this->belongsTo(CabangModel::class);
    }

    public function panggol()
    {
        return $this->belongsTo(PanggolModel::class, 'kd_panggol', 'golongan');
    }

    public function keluarga()
    {
        return $this->hasOne(KeluargaModel::class, 'nip');
    }

    public function gaji() {
        return $this->hasOne(GajiPerBulanModel::class, 'nip');
    }

    public function allGajiByKaryawan() {
        return $this->hasMany(GajiPerBulanModel::class, 'nip');
    }

    public function tunjanganKaryawan() {
        return $this->hasOne(TunjanganKaryawanModel::class, 'nip');
    }

    public function tunjangan()
    {
        return $this->belongsToMany(
            TunjanganModel::class,
            'tunjangan_karyawan',
            'nip',
            'id_tunjangan',
        )->withPivot('nominal');
    }

    public function tunjanganTidakTetap()
    {
        return $this->belongsToMany(
            TunjanganModel::class,
            'penghasilan_tidak_teratur',
            'nip',
            'id_tunjangan'
        )->withPivot('nominal');
    }

    public function sumTunjanganTidakTetapKaryawan()
    {
        return $this->belongsToMany(
            TunjanganModel::class,
            'penghasilan_tidak_teratur',
            'nip',
            'id_tunjangan',
        );
    }

    public function bonus()
    {
        return $this->belongsToMany(
            TunjanganModel::class,
            'penghasilan_tidak_teratur',
            'nip',
            'id_tunjangan',
        )->withPivot('nominal');
    }

    public function sumBonusKaryawan()
    {
        return $this->belongsToMany(
            TunjanganModel::class,
            'penghasilan_tidak_teratur',
            'nip',
            'id_tunjangan',
        );
    }

    public function potonganGaji()
    {
        return $this->belongsTo(PotonganGajiModel::class, 'nip', 'nip');
    }

    public function pphDilunasi() {
        return $this->hasMany(PPHModel::class, 'nip');
    }
}
