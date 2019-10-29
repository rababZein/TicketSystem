<?php

namespace App\Observers;

use App\Role;
use App\Http\Requests\RoleRequest\AddRoleRequest;
use App\Http\Requests\RoleRequest\UpdateRoleRequest;

class RoleObserver
{
    /**
     * Handle the role "created" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        $request = new AddRoleRequest();
        $input = $request->validated();

        $permissionData = $input['permissions'];
        unset($input['permissions']);

        // insert permissions for role
        $role->syncPermissions($permissionData);
    }

    /**
     * Handle the role "updated" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        $request = new UpdateRoleRequest();
        $input = $request->validated();

        $permissionIds = array_column($input['permissions'], 'id');
        unset($input['permissions']);
        $role->syncPermissions($permissionIds);
    }

    /**
     * Handle the role "deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        // get permission of this role
        $permissions = $role->permissions->pluck('name', 'id');

        // revoke(remove) all permission from this role
        if (!empty($permissions)) {
            $role->revokePermissionTo($permissions);
        }
    }

    /**
     * Handle the role "restored" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function restored(Role $role)
    {
        //
    }

    /**
     * Handle the role "force deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function forceDeleted(Role $role)
    {
        //
    }
}
