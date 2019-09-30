<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return $roles;
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    }
}
