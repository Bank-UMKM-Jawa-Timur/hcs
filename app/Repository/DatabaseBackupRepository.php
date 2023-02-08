<?php

namespace App\Repository;

use App\Enum\BackupType;
use Carbon\Carbon;
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

    public function get()
    {
        $data = [];
        $options = $this->options();

        $data['backups'] = array_map(function ($opt) {
            $opt['time'] = new Carbon($opt['time']);
            return $opt;
        }, $options['backups']);

        $data['rollbacks'] = array_map(function ($opt) {
            $opt['time'] = new Carbon($opt['time']);
            return $opt;
        }, $options['rollbacks']);

        $data['position'] = empty($options['position']) ? null : $options['position'];

        return (object) $data;
    }

    public function getById(string $id, BackupType $type)
    {
        $options = $this->options();
        $options = array_filter($options[$type->value], fn ($opt) => $opt['id'] == $id);

        if (empty($options)) return null;

        $options[0]['time'] = new Carbon($options[0]['time']);
        return $options[0];
    }

    public function add(string $name, string $path, BackupType $type)
    {
        $options = $this->options();
        $duplicated = array_filter($options[$type->value], fn ($bc) => $bc['path'] == $path);

        if (!empty($duplicated)) return false;

        $options[$type->value][] = [
            'id' => uniqid("{$type->value}_"),
            'name' => $name,
            'path' => $path,
            'time' => date('Y-m-d H:i:s'),
        ];

        $this->storeOptions($options);
        return true;
    }

    public function changePosition(string $pos, BackupType $type)
    {
        $options = $this->options();
        $options['position'] = [
            'name' => $pos,
            'type' => $type,
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
            'position' => [
                'name' => null,
                'type' => null,
            ],
        ];

        $this->storage->put($this->backupFilename, json_encode($notation));
    }
}
