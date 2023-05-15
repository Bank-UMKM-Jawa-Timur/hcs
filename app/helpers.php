<?php

use App\Models\KaryawanModel;
use App\Models\PjsModel;
use App\Models\PengkinianKaryawanModel;
use App\Models\PengkinianPjsModel;
use App\Service\EntityService;

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