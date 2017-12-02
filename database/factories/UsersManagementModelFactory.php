<?php

/*
|--------------------------------------------------------------------------
| User Management Model Factories
|--------------------------------------------------------------------------
|
| Model factories for users management module
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;

$factory->define(\Acacha\Users\Models\UserInvitation::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'email' => $faker->unique()->safeEmail
    ];
});
