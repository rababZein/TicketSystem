<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class addUserPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete'
        ];
        
        $admin = User::where('name', 'admin')->firstOrFail();

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'created_by' => $admin->id,
                'created_at' => Carbon::now()
            ]);
        }

        // add Permissions to admin role
        $role = Role::where('name', 'admin')->firstOrFail();
        $role->givePermissionTo($permissions);
    }
}
