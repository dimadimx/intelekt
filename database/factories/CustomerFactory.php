<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {

    return [
        'user_id' => $faker->word,
        'uid' => $faker->randomDigitNotNull,
        'gid' => $faker->randomDigitNotNull,
        'login' => $faker->word,
        'phone' => $faker->word,
        'registration' => $faker->date('Y-m-d H:i:s'),
        'warning' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
