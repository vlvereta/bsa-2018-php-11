<?php

namespace App\Response;

use App\User;
use App\Entity\Lot;
use App\Entity\Money;
use App\Entity\Wallet;
use App\Entity\Currency;
use App\Response\Contracts\LotResponse as ILotResponse;

class LotResponse implements ILotResponse
{
    private $user;
    private $lotId;
    private $price;
    private $currency;
    private $dateTimeOpen;
    private $dateTimeClose;

    public function __construct(Lot $lot, User $user, Currency $currency)
    {
        $this->user = $user;
        $this->currency = $currency;
        $this->lotId = $lot->getAttribute('id');
        $this->price = $lot->getAttribute('price');
        $this->dateTimeOpen = $lot->getDateTimeOpen();
        $this->dateTimeClose = $lot->getDateTimeClose();
    }

    public function getId(): int
    {
        return $this->lotId;
    }

    public function getUserName(): string
    {
        return $this->user->getAttribute('name');
    }

    public function getCurrencyName(): string
    {
        return $this->currency->getAttribute('name');
    }

    /*
     * Думаю, что можно сделать более утончённо, но пока так :(
     */
    public function getAmount(): float
    {
        $wallet = Wallet::where('user_id', $this->user->getAttribute('id'))->first();
        $money = Money::where('wallet_id', $wallet->getAttribute('id'))
            ->where('currency_id', $this->currency->getAttribute('id'))->first();
        return $money->getAttribute('amount');
    }

    public function getDateTimeOpen(): string
    {
        return date('Y-m-d H:i:s', $this->dateTimeOpen);
    }

    public function getDateTimeClose(): string
    {
        return date('Y-m-d H:i:s', $this->dateTimeClose);
    }

    public function getPrice(): string
    {
        return number_format($this->price, 2, ',', '');
    }
}