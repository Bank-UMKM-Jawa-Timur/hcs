<?php

namespace App\Helpers;

use App\Models\LogActivityModel;
use App\Repository\LogActivityRepository;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    public static function create($activity)
    {
        $user_id = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->id : auth()->user()->id;
        $repo = new LogActivityRepository();
        $repo->store($user_id, $activity);
    }

    public static function nameBulan($bulan){
        $bulanShow = array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );
        return $bulanShow[$bulan];
    }
}
