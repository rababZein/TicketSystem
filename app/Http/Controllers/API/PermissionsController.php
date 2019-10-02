<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191'
        ]);

        // creating new permission
        $permission = new Permission;
        $permission->name = $request->name;

        // save permission
        $permission->save();

        return $this->sendResponse($permission->toArray(), 'permission created successfully.');
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
        $this->validate($request, [
            'name' => 'required|string|max:191'
        ]);

        // update permission
        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;

        // save permission
        $permission->save();

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
        $permission = Permission::findOrFail($id);

        $roles = $permission->roles->pluck('name', 'id');

        // revoke(remove) this permission from all role
        if (!empty($permissions)) {
            $permission->removeRole($roles);
        }
        
        // delete role
        $permission->delete();

        return $this->sendResponse($permission->toArray(), 'permission deleted successfully.');

    }

    public function getAllPermissions() {
        $permissions = Permission::all();
        return $this->sendResponse($permissions->toArray(), 'permission listed successfully.');
    }
}
