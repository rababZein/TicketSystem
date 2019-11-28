<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class addSettingTicketNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = Setting::create([
            'entity' => 'ticket',
            'start_from' => 'T100000001',
            'current' => true
        ]);
    }
}
