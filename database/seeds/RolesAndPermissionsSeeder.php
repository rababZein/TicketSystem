<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Carbon\Carbon;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create Admin
         /** @var User $admin */
         $admin = factory(User::class)->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'type' => 'regular-user'
        ]);

        // create regular-user
        /** @var User $user */
        $user = factory(User::class)->create(['created_by' => $admin->id]);

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
        ];


        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'created_by' => $admin->id,
                'created_at' => Carbon::now()
             ]);
        }

        Role::create([
            'name' => 'user',
            'created_by' => $admin->id,
            'created_at' => Carbon::now()
        ]);

        $user->assignRole('user');

        $role = Role::create([
            'name' => 'admin',
            'created_by' => $admin->id,
            'created_at' => Carbon::now()
        ]);

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

        $admin->assignRole('admin');
    }
}
