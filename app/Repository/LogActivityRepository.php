<?php

namespace App\Repository;

use App\Helpers\LogActivity;
use App\Models\LogActivityModel;
use Illuminate\Support\Facades\Auth;

class LogActivityRepository
{
    /**
     * @param $user_id
     * @param $activity
     * @return void
     */
    public function store($user_id, $activity): void
    {
        if (Auth::guard('karyawan')->check()) {
            $data = [
                'karyawan_id' => $user_id,
                'activity' => $activity
            ];
        }
        else {
            $data = [
                'user_id' => $user_id,
                'activity' => $activity
            ];
        }
        LogActivityModel::create($data);
    }
}
