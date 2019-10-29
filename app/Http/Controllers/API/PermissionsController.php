<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\PermissionRequest\AddPermissionRequest;
use App\Http\Requests\PermissionRequest\UpdatePermissionRequest;
use App\Http\Requests\PermissionRequest\DeletePermissionRequest;
use App\Http\Requests\PermissionRequest\ViewPermissionRequest;
use App\Http\Requests\PermissionRequest\ListPermissionRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\Permission\PermissionCollection;
use App\Http\Resources\Permission\PermissionResource;
use Carbon\Carbon;


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
        $this->middleware('permission:permission-list', ['only' => ['getAll']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListPermissionRequest $request)
    {
        return view('pages.permissions.index');
    }

    public function getAll(ListPermissionRequest $request)
    {
        $permissions = Permission::all();

        return $this->sendResponse(PermissionResource::collection($permissions), 'Permissions retrieved successfully.');
    }

    public function list(ListPermissionRequest $request)
    {
        $permissions = Permission::paginate();

        return $this->sendResponse(new PermissionCollection($permissions), 'Permissions retrieved successfully.');
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
        } catch (Exception $ex) {
            throw new ItemNotCreatedException('Permission');
        }

        return $this->sendResponse(new PermissionResource($permission), 'permission created successfully.');
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

        return $this->sendResponse(new PermissionResource($permission), 'permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeletePermissionRequest $request, $id)
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

        return $this->sendResponse(new PermissionResource($permission), 'permission deleted successfully.');
    }
}
