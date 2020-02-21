<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ClientStatistic;
use Faker\Generator as Faker;

$factory->define(ClientStatistic::class, function (Faker $faker) {

    return [
        'client_id' => $faker->word,
        'date' => $faker->date('Y-m-d H:i:s'),
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
