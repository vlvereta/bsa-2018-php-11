<?php

namespace App\Request;

use App\Request\Contracts\BuyLotRequest as IBuyLotRequest;

class BuyLotRequest implements IBuyLotRequest
{
    private $lotId;
    private $userId;
    private $amount;

    public function __construct(int $userId, int $lotId, float $amount)
    {
        $this->lotId = $lotId;
        $this->userId = $userId;
        $this->amount = $amount;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getLotId(): int
    {
        return $this->lotId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}