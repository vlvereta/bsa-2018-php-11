<?php

use App\Entity\Money;
use App\Entity\Wallet;
use App\Entity\Currency;
use Faker\Generator as Faker;

$factory->define(Money::class, function (Faker $faker) {
    return [
        'wallet_id'     => factory(Wallet::class)->create()->id,
        'currency_id'   => factory(Currency::class)->create()->id,
        'amount'        => $faker->randomFloat(2, 0, 999999)
    ];
});
