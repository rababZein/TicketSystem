<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->paragraph(mt_rand(2, 4)),
        'responsible_id' => User::where('type', 'regular-user')->get()->random()->id,
        'ticket_id' => Ticket::all()->random()->id,
        'project_id' => Project::all()->random()->id,
        'created_by' => User::where('type', 'regular-user')->get()->random()->id,
    ];
});
