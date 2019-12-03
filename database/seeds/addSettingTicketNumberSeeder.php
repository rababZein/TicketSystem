<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\User;

class addSettingTicketNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('name', 'admin')->firstOrFail();

        $setting = Setting::create([
            'entity' => 'ticket',
            'key' => 'T1',
            'start_number' => '00000001',
            'last_number' => '00000001',
            'current' => true,
            'created_by' => $admin->id
        ]);
    }
}
