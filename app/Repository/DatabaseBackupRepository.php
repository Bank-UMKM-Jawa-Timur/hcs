<?php

namespace App\Repository;

use App\Enum\BackupType;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupRepository
{
    private FilesystemAdapter $storage;
    private string $backupFilename = 'backup_db_log.json';

    public function __construct()
    {
        $this->storage = Storage::disk('local');
        $this->initFile();
    }

    public function add(string $name, string $path, BackupType $type)
    {
        $options = $this->options();
        $duplicated = array_filter($options[$type->value], fn ($bc) => $bc['path'] == $path);

        if (!empty($duplicated)) return false;

        $options[$type->value][] = [
            'name' => $name,
            'path' => $path,
            'time' => date('Y-m-d H:i:s'),
        ];

        $this->storeOptions($options);
        return true;
    }

    private function options()
    {
        $content = $this->storage->get($this->backupFilename);
        return json_decode($content, true);
    }

    private function storeOptions(array $options)
    {
        $this->storage->put(
            $this->backupFilename,
            json_encode($options)
        );
    }

    private function initFile()
    {
        $isExists = $this->storage->fileExists($this->backupFilename);
        if ($isExists) return;

        $notation = [
            'backups' => [],
            'rollbacks' => [],
            'position' => '',
        ];

        $this->storage->put($this->backupFilename, json_encode($notation));
    }
}
