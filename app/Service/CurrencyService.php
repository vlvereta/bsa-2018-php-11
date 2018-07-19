<?php

namespace App\Service;

use App\Entity\Currency;
use App\Request\Contracts\AddCurrencyRequest;
use App\Service\Contracts\CurrencyService as ICurrencyService;
use App\Repository\Contracts\CurrencyRepository as ICurrencyRepository;

class CurrencyService implements ICurrencyService
{
    private $currencyRepository;

    public function __construct(ICurrencyRepository $repository)
    {
        $this->currencyRepository = $repository;
    }

    public function addCurrency(AddCurrencyRequest $currencyRequest): Currency
    {
        return $this->currencyRepository->add(
            new Currency([
                'name'  => $currencyRequest->getName()
            ])
        );
    }
}