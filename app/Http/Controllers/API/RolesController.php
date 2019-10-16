<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\UserRequest\AddRoleRequest;
use App\Http\Requests\UserRequest\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
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
    public function store(AddRoleRequest $request)
    {
        $input = $request->validated();
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;

        // creating new role
        try {
            $role = Role::create($input);
        } catch (\Throwable $th) {
            throw new ItemNotCreatedException('Role');
        }

        // insert permissions for role
        $permissionsArray = [];
        foreach ($input['permissions'] as $permission) {
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
    public function update(UpdateRoleRequest $request, $id)
    {
        // edit role by id
        $role = Role::find($id);
        if (is_null($role)) {
            throw new ItemNotFoundException($id);
        }

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
        try {
            $role->save();
        } catch (\Throwable $th) {
            throw new ItemNotUpdatedException('Role');
        }

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
        $role = Role::find($id);
        if (is_null($role)) {
            throw new ItemNotFoundException($id);
        }

        // get permission of this role
        $permissions = $role->permissions->pluck('name', 'id');

        // revoke(remove) all permission from this role
        if (!empty($permissions)) {
            $role->revokePermissionTo($permissions);
        }

        // delete role
         try {
            $role->delete();
        } catch (\Throwable $th) {
            throw new ItemNotDeletedException('Role');
        }
        return $this->sendResponse(new RoleResource($role), 'role deleted successfully.');
    }
}
