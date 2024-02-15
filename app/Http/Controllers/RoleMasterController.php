<?php

namespace App\Http\Controllers;

use App\Repository\RolesRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class RoleMasterController extends Controller
{
    public $param;
    public function __construct()
    {
        // $permissionNames = auth()->user()->getAllPermissions()->pluck('name');
        // $this->middleware(['auth','permission:'.getPermission($permissionNames)]);
        $this->param = new RolesRepository();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('setting - master - role')) {
            $limit = $request->has('page_length') ? $request->get('page_length') : 10;
            $page = $request->has('page') ? $request->get('page') : 1;

            $search = $request->get('q');
            $search = str_replace("'", "\'", $search);
            $data_role = $this->param->getRoles($search, $limit, $page);
            return view('roles.index', [
                'data' => $data_role,
            ]);
        } else {
            return view('roles.forbidden');

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Auth::user()->can('setting - master - role - create role')) {
            return view('roles.forbidden');
        }
        return view('roles.add',['data' => $this->param->getPermission2($request->q)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $arrayIdPermission = array();
        DB::beginTransaction();
        try{
                $dataInserted = new Role;
                $dataInserted->name = $request->name;
                $dataInserted->guard_name = 'web';
                $dataInserted->created_at = now();
                $dataInserted->save();
                $id = $dataInserted->id;

                foreach($request->id_permissions as $key => $item){
                    array_push($arrayIdPermission, [
                        'permission_id' => $item,
                        'role_id' => $id
                    ]);
                }

                DB::table('role_has_permissions')
                    ->insert($arrayIdPermission);
                DB::commit();
                Alert::success('Berhasil', 'Berhasil menambah Role.');
                return redirect()->route('role.index');
            } catch(Exception $e){
                DB::rollBack();
                dd($e);
                return redirect()->route('role.index')->withError('Terjadi kesalahan. ' . $e->getMessage());
            } catch(QueryException $e){
                DB::rollBack();
                dd($e);
                return redirect()->route('role.index')->withError('Terjadi kesalahan. ' . $e->getMessage());
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!Auth::user()->can('setting - master - role - detail role')) {
            return view('roles.forbidden');
        }
        return view('roles.show',$this->param->getRoleId($id, $request->q));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!Auth::user()->can('setting - master - role - edit role')) {
            return view('roles.forbidden');
        }
        return view('roles.edit',$this->param->getRoleId($id, $request->q));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try{
            $updated = Role::find($id);
            $updated->name = $request->name;
            $fieldToInsert = array();

            if(isset($request->fieldToDelete)){
                foreach($request->fieldToDelete as $item){
                    DB::table('role_has_permissions')
                        ->where('permission_id', $item)
                        ->where('role_id', $id)
                        ->delete();
                }
            }
            if(isset($request->fieldToInsert)){
                foreach($request->fieldToInsert as $item){
                    array_push($fieldToInsert, [
                        'role_id' => $id,
                        'permission_id' => $item
                    ]);
                }
                DB::table('role_has_permissions')
                    ->insert($fieldToInsert);
            }

            $updated->save();
            DB::commit();
            Alert::success('Berhasil', 'Berhasil mengganti Role.');
            return redirect()->route('role.index');
        } catch(Exception $e){
            DB::rollBack();
            dd($e);
            return redirect()->route('role.index')->withError('Terjadi kesalahan. ' . $e->getMessage());
        } catch(QueryException $e){
            DB::rollBack();
            dd($e);
            return redirect()->route('role.index')->withError('Terjadi kesalahan. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            DB::table('roles')
                ->where('id', $id)
                ->delete();

            Alert::success('Berhasil', 'Berhasil Menghapus Data Role.');
            return redirect()->route('role.index');
        } catch(Exception $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('role.index')->withStatus($e->getMessage());
        } catch(QueryException $e){
            Alert::error('Terjadi Kesalahan', ''.$e);
            return redirect()->route('role.index')->withStatus($e->getMessage());
        }
    }
}
