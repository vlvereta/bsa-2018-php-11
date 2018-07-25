<?php

use App\User;
use App\Entity\Wallet;
use Faker\Generator as Faker;

$factory->define(Wallet::class, function (Faker $faker) {
    return [
        'user_id'   => factory(User::class)->create()->id
    ];
});