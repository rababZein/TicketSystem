<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GenerateFakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        factory(App\Models\User::class, 16)->create();
        factory(App\Models\Project::class, 16)->create();
        factory(App\Models\Ticket::class, 16)->create();
        factory(App\Models\Task::class, 16)->create();
    }
}
