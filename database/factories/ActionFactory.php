<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Action;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Action::class, function (Faker $faker) {
    return [
        'location' => $faker->randomElement(["journey",]),
        'action' => $faker->randomElement(["view", "save",]),
        'target' => $faker->name,
        'session_id' => factory(App\Session::class),
    ];
});
