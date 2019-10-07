<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class addTicketPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'ticket-list',
            'ticket-create',
            'ticket-edit',
            'ticket-delete'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // add Permission to admin role
        $role = Role::where('name', 'admin')->firstOrFail();
        $role->givePermissionTo($permissions);
    }
}
