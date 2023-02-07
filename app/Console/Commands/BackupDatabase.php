<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dbDriver = config('database.default');
        $path = 'backup/db/' . date('Y') . '/' . date('M');
        $name = date('Y-m-d_H_i') . '.sql';

        if ($dbDriver == 'mysql') {
            $config = config('database.connections.mysql');

            Storage::makeDirectory($path);
            MySql::create()
                ->setDbName($config['database'])
                ->setUserName($config['username'])
                ->setPassword($config['password'])
                ->dumpToFile(Storage::path("{$path}/{$name}"));
        }

        $this->info("Successfully backed up the database to {$path}/{$name}...");
        return Command::SUCCESS;
    }
}
