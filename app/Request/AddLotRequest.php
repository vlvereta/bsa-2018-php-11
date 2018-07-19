<?php

namespace App\Request;

use App\Request\Contracts\AddLotRequest as IAddLotRequest;

class AddLotRequest implements IAddLotRequest
{
    private $price;
    private $sellerId;
    private $currencyId;
    private $dateTimeOpen;
    private $dateTimeClose;

    public function __construct(int $currencyId, int $sellerId, int $dateTimeOpen, int $dateTimeClose, float $price)
    {
        $this->price = $price;
        $this->sellerId = $sellerId;
        $this->currencyId = $currencyId;
        $this->dateTimeOpen = $dateTimeOpen;
        $this->dateTimeClose = $dateTimeClose;
    }

    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    public function getDateTimeOpen(): int
    {
        return $this->dateTimeOpen;
    }

    public function getDateTimeClose(): int
    {
        return $this->dateTimeClose;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}