<?php

use Faker\Generator as Faker;

$factory->define(App\Payment::class, function (Faker $faker) {
    return [
        "stan" => str_shuffle(time()).'12',
        "transaction_id" => $faker->numerify('############'),
        "amount" => "000000000010",
        "account_number" => "0249621938",
        "description" => "testing from the tester",
        "response_url" => "http://sesmoney.proxy.beeceptor.com"
    ];
});
