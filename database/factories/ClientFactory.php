<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {

    return [
        'user_id' => $faker->word,
        'api_uid' => $faker->randomDigitNotNull,
        'api_gid' => $faker->randomDigitNotNull,
        'api_belong_uid' => $faker->randomDigitNotNull,
        'login' => $faker->word,
        'phone' => $faker->word,
        'registration' => $faker->date('Y-m-d H:i:s'),
        'warning' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
