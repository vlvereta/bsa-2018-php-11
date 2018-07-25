<?php

namespace App\Repository;

use App\Entity\Trade;
use App\Repository\Contracts\TradeRepository as ITradeRepository;

class TradeRepository implements ITradeRepository
{
    public function add(Trade $trade): Trade
    {
        return Trade::create([
            'amount'    => $trade->getAttribute('amount'),
            'lot_id'    => $trade->getAttribute('lot_id'),
            'user_id'   => $trade->getAttribute('user_id'),
        ]);
    }
}