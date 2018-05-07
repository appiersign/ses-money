<?php

use Faker\Generator as Faker;

$factory->define(App\Merchant::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'name' => $faker->name,
        'ses_money_id' => str_random(),
        'merchant_id' => str_random(10),
        'phone_number' => $faker->phoneNumber,
        'address' => $faker->address,
        'password' => bcrypt('admin'),
        'api_user' => $faker->userName,
        'api_key' => str_random(32)
    ];
});

$factory->state(App\Merchant::class, 'active', function (){
    return [
        'is_active' => true
    ];
});
