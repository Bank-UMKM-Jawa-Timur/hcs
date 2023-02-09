<?php

namespace App\Service;

use App\Enum\BackupType;
use App\Exceptions\DatabaseBackupException;
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
        if (!file_exists($backup['path'])) {
            $this->repo->remove($backup['id'], BackupType::BACKUPS);
            throw new DatabaseBackupException("File backup tidak ditemukan");
        }

        Artisan::call('db:backup --type=rollbacks');
        Artisan::call('db:wipe');
        DB::unprepared(file_get_contents($backup['path']));

        $this->repo->changePosition(
            substr($backup['name'], 0, -4),
            BackupType::BACKUPS
        );
    }

    public function rollback($rollback)
    {
        if (!file_exists($rollback['path'])) {
            $this->repo->remove($rollback['id'], BackupType::ROLLBACKS);
            throw new DatabaseBackupException("File rollback tidak ditemukan");
        }

        Artisan::call('db:wipe');
        DB::unprepared(file_get_contents($rollback['path']));

        $this->repo->changePosition(
            substr($rollback['name'], 0, -4),
            BackupType::ROLLBACKS
        );
    }
}
