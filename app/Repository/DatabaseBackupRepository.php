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

        if (!$opt = reset($options)) return null;
        $opt['time'] = new Carbon($opt['time']);

        return $opt;
    }

    public function add(string $name, string $path, BackupType $type)
    {
        $options = $this->options();
        $duplicated = array_filter($options[$type->value], fn ($bc) => $bc['path'] == $path);
        $isSameContent = $this->isSameContent($path, $type);

        if (!empty($duplicated) || $isSameContent) return false;

        array_unshift($options[$type->value], [
            'id' => uniqid("{$type->value}_"),
            'name' => $name,
            'path' => $path,
            'time' => date('Y-m-d H:i:s'),
        ]);

        chmod($path, 0777);
        $this->storeOptions($options);
        return true;
    }

    public function remove(string $id, BackupType $type)
    {
        $options = $this->options();
        $ids = array_map(fn ($opt) => $opt['id'], $options[$type->value]);
        $index = array_search($id, $ids);

        if (!$index) return false;
        array_splice($options[$type->value], $index, 1);
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

    public function checkout()
    {
        $options = $this->options();

        $options['position']['name'] = null;
        $options['position']['type'] = null;

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

    private function isSameContent(string $path, BackupType $type): bool
    {
        if ($type == BackupType::BACKUPS) return false;

        $options = $this->options()[$type->value];
        $md5sums = array_map(function ($opt) {
            $path = $opt['path'];

            if (!file_exists($path)) return null;
            return md5_file($path);
        }, $options);

        return in_array(md5_file($path), $md5sums);
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
