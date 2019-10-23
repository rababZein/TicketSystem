<?php

use Illuminate\Database\Seeder;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;

class addStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            'open',
            'pending',
            'in-progress',
            'done'
        ];

        $admin = User::where('name', 'admin')->firstOrFail();

        foreach ($status as $one) {
            Status::create([
                'name' => $one,
                'main' => 1,
                'created_by' => $admin->id,
                'created_at' => Carbon::now()
                ]);
        }
    }
}
