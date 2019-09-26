<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');


        Role::create(['name' => 'user']);
        /** @var \App\User $user */
        $user = factory(\App\User::class)->create();

        $user->assignRole('user');
        $role = Role::create(['name' => 'admin']);

        $role->givePermissionTo([
        'role-list',
        'role-create',
        'role-edit',
        'role-delete',
        'permission-list',
        'permission-create',
        'permission-edit',
        'permission-delete'
        ]);

        /** @var \App\User $user */
        $admin = factory(\App\User::class)->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            // default password "password"
        ]);

        $admin->assignRole('admin');
    }
}
