<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Project;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->paragraph(mt_rand(2, 4)),
        'owner_id' => User::where('type', 'client')->get()->random()->id,
        'task_rate' => $faker->numberBetween($min = 1, $max = 10),
        'budget_hours' => $faker->numberBetween($min = 1, $max = 10),
        'created_by' => User::where('type', 'regular-user')->get()->random()->id,
    ];
});
