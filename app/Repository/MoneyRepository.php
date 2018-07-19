<?php

namespace App\Repository;

use App\Entity\Money;
use App\Repository\Contracts\MoneyRepository as IMoneyRepository;

class MoneyRepository implements IMoneyRepository
{
    public function save(Money $money): Money
    {
        $amount = $money->getAttribute('amount');
        $walletId = $money->getAttribute('wallet_id');
        $currencyId = $money->getAttribute('currency_id');

        if ($existed = $this->findByWalletAndCurrency($walletId, $currencyId)) {
            $existed->setAttribute('amount', $amount);
            $existed->save();
            return $existed;
        }
        return Money::create([
            'amount'        => $amount,
            'wallet_id'     => $walletId,
            'currency_id'   => $currencyId,
        ]);
    }

    public function findByWalletAndCurrency(int $walletId, int $currencyId): ?Money
    {
        return Money::where('wallet_id', $walletId)->where('currency_id', $currencyId)->first();
    }
}