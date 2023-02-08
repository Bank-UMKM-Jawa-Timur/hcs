<?php

namespace App\Console\Commands;

use App\Enum\BackupType;
use App\Repository\DatabaseBackupRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--type=backups : Backup Type (backups, rollbacks)}';
    protected $description = 'Backup the database';

    private DatabaseBackupRepository $repo;

    public function __construct()
    {
        parent::__construct();
        $this->repo = new DatabaseBackupRepository;
    }

    public function handle()
    {
        $backupType = BackupType::from($this->option('type'));
        $dbDriver = config('database.default');
        $path = 'backup/db/' . date('Y') . '/' . date('M');
        $name = date('Y-m-d_H_i') . '.sql';

        if ($dbDriver == 'mysql') {
            $config = config('database.connections.mysql');
            $sPath = Storage::path("{$path}/{$name}");

            Storage::makeDirectory($path);
            MySql::create()
                ->setDbName($config['database'])
                ->setUserName($config['username'])
                ->setPassword($config['password'])
                ->dumpToFile($sPath);

            $this->repo->add($name, $sPath, $backupType);
        }

        $this->info("Successfully backed up the database to {$path}/{$name}...");
        return Command::SUCCESS;
    }
}
