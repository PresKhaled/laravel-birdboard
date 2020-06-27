<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Project;
use App\User;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'owner_id' => factory('App\User'),
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'notes' => $faker->sentence
    ];
});
