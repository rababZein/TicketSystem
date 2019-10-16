<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;

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
        return $this->sendResponse(RoleResource::collection($roles), 'roles retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191'
        ]);

        // creating new role
        $role = new Role;
        $role->name = $request->name;

        // insert permissions for role
        $permissionsArray = [];
        foreach ($request->permissions as $permission) {
            $permissionsArray[] = $permission['name'];
        }
        $role->syncPermissions($permissionsArray);
        
        // save role
        $role->save();

        return $this->sendResponse(new RoleResource($role), 'role created successfully.');
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
        // edit role by id
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        
        // insert permissions for role
        $permissionsArray = [];
        foreach ($request->permissions as $permission) {
            $permissionsArray[] = $permission['name'];
        }
        $role->syncPermissions($permissionsArray);

        // save role
        $role->save();

        return $this->sendResponse(new RoleResource($role), 'role updated successfully.');

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
        return $this->sendResponse(new RoleResource($role), 'role deleted successfully.');
    }
}
