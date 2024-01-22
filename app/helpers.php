<?php

use App\Models\KaryawanModel;
use App\Models\PjsModel;
use App\Models\PengkinianKaryawanModel;
use App\Models\PengkinianPjsModel;
use App\Service\EntityService;
use Illuminate\Support\Facades\DB;

if (!function_exists('getMonth()')) {
    function getMonth(int $index, bool $indexed = false)
    {
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return ($indexed) ? $months[$index] : $months[$index - 1];
    }
}

if (!function_exists('npwp')) {
    function npwp($value)
    {
        if (is_null($value)) return null;

        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{1})(\d{3})(\d{3})/',
            '$1.$2.$3.$4-$5.$6',
            $value
        );
    }
}

if (!function_exists('toRupiah')) {
    function toRupiah($value)
    {
        if (is_null($value)) return '-';
        return number_format($value, 0, ".", ".");
    }
}

if (!function_exists('abbrevPos')) {
    function abbrevPos($name)
    {
        return EntityService::abbrevPos($name);
    }
}

if (!function_exists('jabatanLengkap')) {
    function jabatanLengkap(KaryawanModel|PjsModel $model)
    {
        return EntityService::getPosition($model);
    }
}

if (!function_exists('jabatanLengkapPengkinian')) {
    function jabatanLengkapPengkinian(PengkinianKaryawanModel|PengkinianPjsModel $model)
    {
        return EntityService::getPositionPengkinian($model);
    }
}

if(!function_exists('getSKTerakhir')) {
    function getSKTerakhir($nip){
        $data = DB::table('demosi_promosi_pangkat')
            ->where('nip', $nip)
            ->select('bukti_sk')
            ->orderBy('tanggal_pengesahan', 'desc')
            ->first();

        return $data != null ? $data->bukti_sk : null;
    }
}

if (!function_exists('getPermission')) {
    function getPermission(int $index = 0, int $count = 1) {
        $permission = [
            // dashboard
            'dashboard',
            // manajemen karyawan
            'manajemen karyawan',
            'manajemen karyawan - data karyawan',
            'manajemen karyawan - data karyawan - create karyawan',
            'manajemen karyawan - data karyawan - edit karyawan',
            'manajemen karyawan - data karyawan - detail karyawan',
            'manajemen karyawan - data karyawan - import karyawan',
            'manajemen karyawan - data karyawan - export karyawan',
            // data masa pensiunan,
            'manajemen karyawan - data masa pensiunan',
            'manajemen karyawan - update data masa pensiunan',
            'manajemen karyawan - import data masa pensiunan',
            'manajemen karyawan - detail data masa pensiunan',
            'manajemen karyawan - pengkinian data',
            'manajemen karyawan - pengkinian data - update pengkinian data',
            'manajemen karyawan - pengkinian data - import pengkinian data',
            'manajemen karyawan - pengkinian data - detail pengkinian data',
            'manajemen karyawan - pergerakan karir',
            'manajemen karyawan - pergerakan karir - data mutasi',
            'manajemen karyawan - pergerakan karir - data mutasi - create mutasi',
            'manajemen karyawan - pergerakan karir - data demosi',
            'manajemen karyawan - pergerakan karir - data demosi - create demosi',
            'manajemen karyawan - pergerakan karir - data promosi',
            'manajemen karyawan - pergerakan karir - data promosi - create promosi',
            'manajemen karyawan - pergerakan karir - data penonaktifan karyawan',
            'manajemen karyawan - pergerakan karir - data penonaktifan karyawan - tambah penonaktifan karyawan',
            'manajemen karyawan - data penjabat sementara',
            'manajemen karyawan - tambah penjabat sementara',
            'manajemen karyawan - reward & punishment',
            'manajemen karyawan - reward & punishment - surat peringatan',
            'manajemen karyawan - reward & punishment - surat peringatan - create',
            'manajemen karyawan - reward & punishment - surat peringatan - detail',
            'penghasilan',
            'penghasilan - proses penghasilan',
            'penghasilan - pajak penghasilan',
            'penghasilan - tambah penghasilan',
            'penghasilan - tambah penghasilan - import penghasilan',
            'histori',
            'histori - jabatan',
            'histori - penjabat sementara',
            'histori - surat peringatan',
            'laporan',
            'laporan - laporan pergerakan karir',
            'laporan - laporan pergerakan karir - laporan mutasi',
            'laporan - laporan pergerakan karir - laporan demosi',
            'laporan - laporan pergerakan karir - laporan promosi',
            'laporan - laporan pergerakan karir - laporan penonaktifan',
            'laporan - laporan jamsostek',
            'laporan - laporan dpp',
            'gaji',
            'gaji - lampiran gaji',
            'gaji - slip jurnal',
            'migrasi',
            'migrasi - jabatan',
            'migrasi - penjabat sementara',
            'migrasi - surat peringatan',
            'log',
            'log - log aktivitas',
            'setting',
            'setting - master',
            'setting - master - role',
            'setting - master - role - create role',
            'setting - master - role - edit role',
            'setting - master - role - detail role',
            'setting - master - role - delete role',
            'setting - master - kantor cabang',
            'setting - master - kantor cabang - create kantor cabang',
            'setting - master - kantor cabang - edit kantor cabang',
            'setting - master - divisi',
            'setting - master - divisi - create divisi',
            'setting - master - divisi - edit divisi',
            'setting - master - sub divisi',
            'setting - master - sub divisi - create sub divisi',
            'setting - master - sub divisi - edit sub divisi',
            'setting - master - bagian',
            'setting - master - bagian - create bagian',
            'setting - master - bagian - edit bagian',
            'setting - master - jabatan',
            'setting - master - jabatan - create jabatan',
            'setting - master - jabatan - edit jabatan',
            'setting - master - pangkat & golongan',
            'setting - master - pangkat & golongan - create pangkat & golongan',
            'setting - master - pangkat & golongan - edit pangkat & golongan',
            'setting - master - tunjangan',
            'setting - master - tunjangan - create tunjangan',
            'setting - master - tunjangan - edit tunjangan',
            'setting - master - rentang umur',
            'setting - master - rentang umur - create rentang umur',
            'setting - master - rentang umur - edit rentang umur',
            'setting - master - penghasilan tanpa pajak',
            'setting - master - penghasilan tanpa pajak - create penghasilan tanpa pajak',
            'setting - master - penghasilan tanpa pajak - edit penghasilan tanpa pajak',
            'setting - kantor pusat',
            'setting - kantor pusat - profil',
            'setting - kantor pusat - penambahan bruto',
            'setting - kantor pusat - penambahan bruto - create penambahan bruto',
            'setting - kantor pusat - penambahan bruto - edit penambahan bruto',
            'setting - kantor pusat - penambahan bruto - delete penambahan bruto',
            'setting - kantor pusat - pengurangan bruto',
            'setting - kantor pusat - pengurangan bruto - create pengurangan bruto',
            'setting - kantor pusat - pengurangan bruto - edit pengurangan bruto',
            'setting - kantor pusat - pengurangan bruto - delete pengurangan bruto',
            'setting - database',
        ];
        // return count($permission);
        if ($count > count($permission)) {
            return false;
        }
        $array = [];
        for ($i=$index; $i < $count; $i++) {
            array_push($array,$permission[$i]);
        }

        // $result = '';
        // for ($j=0; $j < count($array); $j++) {
        //     if ($j + 1 < count($array)) {
        //         $result .= $array[$j].'|'.$array[$j + 1].'|';
        //     }else{
        //         $result .= $array[$j] . '';
        //     }
        // }
        return $array;

    }
}

if (!function_exists('hasPermission')) {
    function hasPermission(string $permission_name = '') {
        $permissions = auth()->user()->getAllPermissions();
        foreach ($permissions as $key => $value) {
            if ($value->name == $permission_name) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('formatRupiahExcel')) {
    function formatRupiahExcel(int $number, int $precission = 0, $formated = false) {
        $number_formated = number_format($number, $precission, ',', '.');
        if ($number < 0) {
            return $formated ? '('.str_replace('-', '', $number_formated).')' : $number;
        }
        else if ($number == 0) {
            return '-';
        }
        else {
            return $formated ? $number_formated : $number;
        }
    }
}
