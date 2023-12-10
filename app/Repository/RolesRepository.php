    <?php

    namespace App\Repository;

    use App\Http\Controllers\Utils\PaginationController;
    use App\Models\CabangModel;
    use App\Models\KaryawanModel;
    use App\Service\EntityService;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Spatie\Permission\Models\Role;

    class RolesRepository
    {
        private $param;

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

        public function getRoleId($id) {
            $this->param['data'] = Role::find($id);
            $this->param['dataPermissions'] = DB::table('permissions')
                ->get();
            $selected = DB::table('role_has_permissions')
                ->where('role_id', $id)
                ->get();
            $arraySelected = array();
            foreach($selected as $item){
                array_push($arraySelected, $item->permission_id);
            }
            $this->param['selected'] = DB::table('role_has_permissions')
                                    ->where('role_id',$id)
                                    ->join('permissions', 'role_has_permissions.permission_id', 'permissions.id')
                                    ->get();
            $this->param['dataPermissionsSelected'] = $arraySelected;
            return $this->param;
        }
    }
