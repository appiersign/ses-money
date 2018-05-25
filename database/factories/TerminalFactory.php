<?php

use Faker\Generator as Faker;

$factory->define(\App\Terminal::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'ses_money_id' => str_random(12)
    ];
});
