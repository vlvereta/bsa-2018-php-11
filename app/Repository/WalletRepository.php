<?php

namespace App\Repository;

use App\Entity\Wallet;
use App\Repository\Contracts\WalletRepository as IWalletRepository;

class WalletRepository implements IWalletRepository
{
    public function add(Wallet $wallet): Wallet
    {
        return Wallet::create([
            'user_id'   => $wallet->getAttribute('user_id')
        ]);
    }

    public function findByUser(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->first();
    }
}