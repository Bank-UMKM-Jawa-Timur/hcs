<?php

namespace App\Repository;

use App\Http\Controllers\Utils\PaginationController;
use App\Models\CabangModel;
use App\Models\KaryawanModel;
use App\Service\EntityService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolesRepository
{
    public function getRoles($search, $limit=10, $page=1) {
        $role_permissions = Role::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");

        })
        ->paginate($limit);
        return $role_permissions;
    }

    public function getPermission() {
        return DB::table('permissions')->get();
    }
}
