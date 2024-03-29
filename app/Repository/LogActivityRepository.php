<?php

namespace App\Repository;

use App\Helpers\LogActivity;
use App\Models\LogActivityModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LogActivityRepository
{
    public function list($search, $page_length=10, $page=1)
    {
        $data = LogActivityModel::select(
            'log_activities.*',
            'u.name AS user',
            'k.nama_karyawan AS karyawan'
        )
            ->leftJoin('users AS u', 'u.id', 'log_activities.user_id')
            ->leftJoin('mst_karyawan AS k', 'k.id', 'log_activities.karyawan_id')
            ->where('u.name', 'like', "%$search%")
            ->orWhere('k.nama_karyawan', 'like', "%$search%")
            ->orWhere('log_activities.activity', 'like', "%$search%")
            ->orderByDesc('log_activities.created_at')
            ->paginate($page_length);

        // Convert created_at field to human-readable format
        $data->getCollection()->transform(function ($item) {
            $item->created_at_human_readable = Carbon::parse($item->created_at)->diffForHumans();
            return $item;
        });
        return $data;
    }

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
