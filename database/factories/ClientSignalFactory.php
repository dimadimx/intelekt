<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ClientSignal;
use Faker\Generator as Faker;

$factory->define(ClientSignal::class, function (Faker $faker) {

    return [
        'client_id' => $faker->word,
        'date' => $faker->date('Y-m-d H:i:s'),
        'value' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
