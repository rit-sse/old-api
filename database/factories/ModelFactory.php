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

$factory->define(App\AgendaItem::class, function (Faker\Generator $faker) {
    return [
        'body' => $faker->sentence(),
        'group_id' => $faker->numberBetween(1, 50),
    ];
});

$factory->define(App\Event::class, function (Faker\Generator $faker) {
    return [
        'description' => $faker->paragraph(),
        'end_date' => $faker->dateTimeThisYear(),
        'featured' => $faker->boolean(),
        'group_id' => $faker->numberBetween(1, 50),
        'image' => str_random(10),
        'location' => $faker->regexify('[A-Z]{3}-\d{4}'),
        'name' => $faker->word(),
        'recurrence' => '',
        'short_description' => $faker->sentence(),
        'short_name' => $faker->word(),
        'start_date' => $faker->dateTimeThisYear(),
    ];
});

$factory->define(App\ExternalProfile::class, function (Faker\Generator $faker) {
    return [
        'identifier' => 'U' . str_random(9),
        'provider' => 'slack',
    ];
});

$factory->define(App\Group::class, function (Faker\Generator $faker) {
    return [
        'officer_id' => $faker->numberBetween(1, 50),
        'name' => $faker->name,
    ];
});

$factory->define(App\Lingo::class, function (Faker\Generator $faker) {
    return [
        'phrase' => $faker->word(),
        'definition' => $faker->paragraph(),
    ];
});

$factory->define(App\Member::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => $faker->regexify('[a-z]{2,3}\d{4}@g\.rit\.edu'),
        'created_at' => $faker->dateTimeThisYear(),
        'updated_at' => $faker->dateTimeThisYear()
    ];
});

$factory->define(App\Officer::class, function (Faker\Generator $faker) {
    return [
        'member_id' => $faker->numberBetween(1, 50),
        'term_id' => $faker->numberBetween(1, 32),
        'title' => $faker->name,
    ];
});

$factory->define(App\Tip::class, function (Faker\Generator $faker) {
    return [
        'body' => $faker->paragraph(),
        'member_id' => $faker->numberBetween(1, 50),
    ];
});

/* Member->Profile Factory */
$factory->define(App\ExternalProfile::class, function (Faker\Generator $faker) {
    return [
        'identifier' => 'U' . str_random(9),
        'provider' => 'slack',
    ];
});

/* Task Factory */
$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Random Task ' . $faker->text(30),
        'description' => $faker->text(100),
        'creator_id' => 1,
        'assignee_id' => 1,
        'completed' => false,
    ];
});


