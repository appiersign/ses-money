<?php

use Faker\Generator as Faker;

$factory->define(App\Payment::class, function (Faker $faker) {
    return [
        "stan" => str_shuffle(time()).'12',
        "transaction_id" => $faker->randomDigit,
        "amount" => "000000000010",
        "description" => "testing from the tester",
        "response_url" => "https://api.theteller.net"
    ];
});
