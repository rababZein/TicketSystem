<?php

namespace App\Observers;

use App\Permission;

class PermissionObserver
{
    /**
     * Handle the permission "created" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function created(Permission $permission)
    {
        //
    }

    /**
     * Handle the permission "updated" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function updated(Permission $permission)
    {
        //
    }

    /**
     * Handle the permission "deleted" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function deleted(Permission $permission)
    {
        $roles = $permission->roles->pluck('name', 'id');

        // revoke(remove) this permission from all role
        if (!empty($roles)) {
            $permission->removeRole($roles);
        }
    }

    /**
     * Handle the permission "restored" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function restored(Permission $permission)
    {
        //
    }

    /**
     * Handle the permission "force deleted" event.
     *
     * @param  \App\Permission  $permission
     * @return void
     */
    public function forceDeleted(Permission $permission)
    {
        //
    }
}
