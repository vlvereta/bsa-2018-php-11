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

    public function __construct(Lot $lot)
    {
        $this->lotId = $lot->getAttribute('id');
        $this->price = $lot->getAttribute('price');
        $this->dateTimeOpen = $lot->getAttribute('date_time_open');
        $this->dateTimeClose = $lot->getAttribute('date_time_close');
        $this->user = User::find($lot->getAttribute('seller_id'));
        $this->currency = Currency::find($lot->getAttribute('currency_id'));
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
        $wallet = Wallet::where('user_id', $this->user->getAttribute('id'));
        $money = Money::where('wallet_id', $wallet->getAttribute('id'))
            ->where('currency_id', $this->currency->getAttribute('id'));
        return $money->getAttribute('amount');
    }

    public function getDateTimeOpen(): string
    {
        return date("Y-m-d H:i:s", $this->dateTimeOpen);
    }

    public function getDateTimeClose(): string
    {
        return date("Y-m-d H:i:s", $this->dateTimeClose);
    }

    public function getPrice(): string
    {
        return number_format($this->price, 2, ',', '');
    }
}