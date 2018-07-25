<?php

namespace App\Service;

use App\Entity\Money;
use App\Entity\Wallet;
use App\Request\Contracts\MoneyRequest;
use App\Request\Contracts\CreateWalletRequest;
use App\Service\Contracts\WalletService as IWalletService;
use App\Repository\Contracts\MoneyRepository as IMoneyRepository;
use App\Repository\Contracts\WalletRepository as IWallerRepository;

class WalletService implements IWalletService
{
    private $moneyRepository;
    private $walletRepository;

    public function __construct(IMoneyRepository $moneyRepository, IWallerRepository $walletRepository)
    {
        $this->moneyRepository = $moneyRepository;
        $this->walletRepository = $walletRepository;
    }

    public function addWallet(CreateWalletRequest $walletRequest): Wallet
    {
        $userId = $walletRequest->getUserId();
        $wallet = $this->walletRepository->findByUser($userId);
        if (!$wallet) {
            return $this->walletRepository->add(
                new Wallet([
                    'user_id'   => $userId
                ])
            );
        }
        return $wallet;
    }

    public function addMoney(MoneyRequest $moneyRequest): Money
    {
        $amount = $moneyRequest->getAmount();
        $walletId = $moneyRequest->getWalletId();
        $currencyId = $moneyRequest->getCurrencyId();

        $money = $this->moneyRepository->findByWalletAndCurrency($walletId, $currencyId);
        if ($money) {
            $newAmount = $money->getAttribute('amount') + $amount;
            $money->setAttribute('amount', $newAmount);
            return $this->moneyRepository->save($money);
        }
        return $this->moneyRepository->save(
          new Money([
              'amount'      => $amount,
              'wallet_id'   => $walletId,
              'currency_id' => $currencyId,
          ])
        );
    }

    public function takeMoney(MoneyRequest $moneyRequest): Money
    {
        $amount = $moneyRequest->getAmount();
        $walletId = $moneyRequest->getWalletId();
        $currencyId = $moneyRequest->getCurrencyId();

        $money = $this->moneyRepository->findByWalletAndCurrency($walletId, $currencyId);
        if ($money) {
            $amountInWallet = $money->getAttribute('amount');
            if ($amountInWallet >= $amount) {
                $newAmount = $amountInWallet - $amount;
                $money->setAttribute('amount', $newAmount);
                return $this->moneyRepository->save($money);
            }
        }
        return $money;
    }
}