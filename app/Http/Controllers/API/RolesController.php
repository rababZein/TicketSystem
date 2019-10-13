<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\UserRequest\AddRoleRequest;
use App\Http\Requests\UserRequest\UpdateRoleRequest;
use Spatie\Permission\Models\Role;

class RolesController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'list']]);
        $this->middleware('permission:role-create', ['only' => ['store']]);
        $this->middleware('permission:role-edit', ['only' => ['update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.roles.index');
    }

    public function list()
    {
        $roles = Role::with('permissions')->paginate(10);
        return $this->sendResponse($roles->toArray(), 'roles retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddRoleRequest $request)
    {
        $input = $request->validated();
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;

        // creating new role
        $role = Role::create($input);

        // insert permissions for role
        $permissionsArray = [];
        foreach ($input['permissions'] as $permission) {
            $permissionsArray[] = $permission['name'];
        }
        $role->syncPermissions($permissionsArray);
        
        // save role
        $role->save();

        return $this->sendResponse($role->toArray(), 'role created successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        // edit role by id
        $role = Role::findOrFail($id);

        $input = $request->validated();

        $role->updated_at = Carbon::now();
        $role->updated_by = auth()->user()->id;

        $role = $role->fill($input);
        
        // insert permissions for role
        $permissionsArray = [];
        foreach ($input['permissions'] as $permission) {
            $permissionsArray[] = $permission['name'];
        }
        $role->syncPermissions($permissionsArray);

        // save role
        $role->save();

        return $this->sendResponse($role->toArray(), 'role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // find the role
        $role = Role::findOrFail($id);

        // get permission of this role
        $permissions = $role->permissions->pluck('name', 'id');

        // revoke(remove) all permission from this role
        if (!empty($permissions)) {
            $role->revokePermissionTo($permissions);
        }

        // delete role
        $role->delete();
        return $this->sendResponse($role->toArray(), 'role deleted successfully.');
    }
}
