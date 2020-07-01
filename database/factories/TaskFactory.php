<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Task;
use App\Project;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'project_id' => factory(Project::class),
        'body' => $faker->sentence,
        'completed' => false
    ];
});
