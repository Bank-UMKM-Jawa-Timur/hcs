<?php

namespace App\Service;

use App\Enum\BackupType;
use App\Repository\DatabaseBackupRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseService
{
    private DatabaseBackupRepository $repo;

    public function __construct()
    {
        $this->repo = new DatabaseBackupRepository;
    }

    public function restore($backup)
    {
        Artisan::call('db:wipe');
        Artisan::call('db:backup --type=rollbacks');
        DB::unprepared(file_get_contents($backup['path']));

        $this->repo->changePosition(
            substr($backup['name'], 0, -4),
            BackupType::BACKUPS
        );
    }
}
