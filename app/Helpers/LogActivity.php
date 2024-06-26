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
}
