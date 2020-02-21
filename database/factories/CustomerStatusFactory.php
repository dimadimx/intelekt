<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CustomerStatus;
use Faker\Generator as Faker;

$factory->define(CustomerStatus::class, function (Faker $faker) {

    return [
        'customer_id' => $faker->randomDigitNotNull,
        'date' => $faker->date('Y-m-d H:i:s'),
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
