<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            addUserPermissionsSeeder::class,
            addProjectPermissionsSeeder::class,
            addTicketPermissionsSeeder::class,
            addTaskPermissionsSeeder::class,
            addReceiptPermissionsSeeder::class,
            addTrackTaskPermissionsSeeder::class
        ]);
    }
}
