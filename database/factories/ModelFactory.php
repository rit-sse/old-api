<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Member::class, function (Faker\Generator $faker) {
    return [
        'first_name' => str_random(8),
        'last_name' => str_random(8),
        'email' => str_random(7) . '@g.rit.edu',
    ];
});

$factory->define(App\ExternalProfile::class, function (Faker\Generator $faker) {
    return [
        'identifier' => 'U' . str_random(9),
        'provider' => 'slack',
    ];
});
