<?php

namespace App\Http\Controllers;

use App\Enum\BackupType;
use App\Exceptions\DatabaseBackupException;
use App\Repository\DatabaseBackupRepository;
use App\Service\DatabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DatabaseController extends Controller
{
    private DatabaseBackupRepository $repo;
    private DatabaseService $service;

    public function __construct()
    {
        $this->repo = new DatabaseBackupRepository;
        $this->service = new DatabaseService;
    }

    public function index()
    {
        return view('database.index', [
            'database' => $this->repo->get(),
        ]);
    }

    public function restore($id)
    {
        $backup = $this->repo->getById($id, BackupType::BACKUPS);
        if (!$backup) return abort(404);

        try {
            $this->service->restore($backup);
            Alert::success('Restore Database Berhasil');
        } catch (DatabaseBackupException $e) {
            Alert::error($e->getMessage());
        }

        return back();
    }
}
