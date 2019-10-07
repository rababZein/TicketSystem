<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class addProjectPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'project-list',
            'project-create',
            'project-edit',
            'project-delete'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // add Permission to admin role
        $role = Role::where('name', 'admin')->firstOrFail();
        $role->givePermissionTo($permissions);
    }
}
