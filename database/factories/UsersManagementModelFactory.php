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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Acacha\Users\Models\UserMigration::class, function (Faker\Generator $faker) {
    return [
        'source_user_id' => $id = $faker->unique()->numberBetween($min = 1, $max = 10000),
        'source_user' => '{"id":' . $id . ',"person_id":'. $faker->unique()->numberBetween($min = 1, $max = 10000) . ',"ip_address":"'. $faker->ipv4 .'","username":"'. $faker->username .'","password":"'. $faker->md5 .'","initial_password":"'.$faker->password.'","force_change_password_next_login":"y","mainOrganizationaUnitId":99,"salt":null,"secondary_email":"'. $faker->unique()->email.'","activation_code":null,"forgotten_password_realm":"ldap","forgotten_password_code":"4493eddec53973bdf2d3264936013b1c","forgotten_password_time":1412090986,"remember_code":null,"created_on":"2011-09-14 15:32:37","last_modification_date":"2017-01-18 12:18:02","last_modification_user":3314,"creation_user":2,"last_login":"2015-01-28 13:23:42","active":1,"mark_as_not_duplicated":0,"ldap_dn":"cn=' . $faker->name . ',ou=Alumnes,ou=All,dc=iesebre,dc=com","user_key":0,"person":null}',
        'user_id' => factory(User::class)->create()->id
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Acacha\Users\Models\ProgressBatch::class, function (Faker\Generator $faker) {
    return [
        'accomplished' => $id = $faker->unique()->numberBetween($min = 1, $max = 10000),
        'incidences' => $id = $faker->unique()->numberBetween($min = 1, $max = 10000),
        'state' => $faker->randomElement(['pending','finished','stopped']),
        'type' => 'App\User'
    ];
});
