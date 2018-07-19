<?php

namespace App\Request;

use App\Request\Contracts\AddCurrencyRequest as ICurrencyRequest;

class AddCurrencyRequest implements ICurrencyRequest
{
    private $currencyName;

    public function __construct(string $name)
    {
        $this->currencyName = $name;
    }

    public function getName(): string
    {
        return $this->currencyName;
    }
}