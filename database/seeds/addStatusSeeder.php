<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

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
        foreach ($status as $one) {
            Status::create(['name' => $one]);
        }
    }
}
