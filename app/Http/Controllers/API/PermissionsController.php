<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\PermissionRequest\AddPermissionRequest;
use App\Http\Requests\PermissionRequest\UpdatePermissionRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;

class PermissionsController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'list']]);
        $this->middleware('permission:permission-create', ['only' => ['store']]);
        $this->middleware('permission:permission-edit', ['only' => ['update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
        $this->middleware('permission:permission-list', ['only' => ['getAllPermissions']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.permissions.index');
    }

    public function list()
    {
        $permissions = Permission::paginate(10);

        return $this->sendResponse($permissions->toArray(), 'Permissions retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddPermissionRequest $request)
    {
        $input = $request->validated();

        // creating new permission
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;

        try {
            $permission = Permission::create($input);
        } catch (\Throwable $th) {
            throw new ItemNotCreatedException('Permission');
        }

        return $this->sendResponse($permission->toArray(), 'permission created successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        // update permission
        $permission = Permission::find($id);
        if (is_null($permission)) {
            throw new ItemNotFoundException($id);
        }
        
        $input = $request->validated();

        $permission->updated_at = Carbon::now();
        $permission->updated_by = auth()->user()->id;

        try {
            $permission = $permission->fill($input)->save();
        } catch (\Throwable $th) {
            throw new ItemNotUpdatedException('Project');
        }

        return $this->sendResponse($permission->toArray(), 'permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete permission
        $permission = Permission::find($id);
        if (is_null($permission)) {
            throw new ItemNotFoundException($id);
        }

        $roles = $permission->roles->pluck('name', 'id');

        // revoke(remove) this permission from all role
        if (!empty($permissions)) {
            $permission->removeRole($roles);
        }
        
        try {
            $permission->delete();
        } catch (\Throwable $th) {
            throw new ItemNotDeletedException('Project');
        }

        return $this->sendResponse($permission->toArray(), 'permission deleted successfully.');
    }

    public function getAllPermissions() {
        $permissions = Permission::all();

        return $this->sendResponse($permissions->toArray(), 'permission listed successfully.');
    }
}
