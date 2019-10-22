<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class addReceiptPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'receipt-list',
            'receipt-create',
            'receipt-edit',
            'receipt-delete'
        ];
        
        $admin = User::where('name', 'admin')->firstOrFail();

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'created_by' => $admin->id,
                'created_at' => Carbon::now()
            ]);
        }

        // add Permission to admin role
        $role = Role::where('name', 'admin')->firstOrFail();
        $role->givePermissionTo($permissions);
    }
}
