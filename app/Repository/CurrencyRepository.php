<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Repository\Contracts\CurrencyRepository as ICurrencyRepository;

class CurrencyRepository implements ICurrencyRepository
{
    public function add(Currency $currency): Currency
    {
        return Currency::create(
            ['name' => $currency->getAttribute('name')]
        );
    }

    public function getById(int $id): ?Currency
    {
        return Currency::find($id);
    }

    public function getCurrencyByName(string $name): ?Currency
    {
        return Currency::where('name', $name)->first();
    }

    public function findAll()
    {
        return Currency::all()->toArray();
    }
}