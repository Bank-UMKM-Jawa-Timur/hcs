<?php

namespace App\Helpers;

use App\Models\LogActivityModel;

class LogActivity
{
    public static function create($user_id = null, $activity) {
        if (!$user_id) 
            $user_id = auth()->user()->id;

        LogActivityModel::create([
            'user_id' => $user_id,
            'activity' => $activity
        ]);
    }
}