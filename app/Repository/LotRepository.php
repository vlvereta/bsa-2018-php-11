<?php

namespace App\Repository;

use App\Entity\Lot;
use App\Repository\Contracts\LotRepository as ILotRepository;

class LotRepository implements ILotRepository
{
    public function add(Lot $lot): Lot
    {
        return Lot::create([
            'price'             => $lot->getAttribute('price'),
            'seller_id'         => $lot->getAttribute('seller_id'),
            'currency_id'       => $lot->getAttribute('currency_id'),
            'date_time_open'    => date("Y-m-d H:i:s", $lot->getAttribute('date_time_open')),
            'date_time_close'   => date("Y-m-d H:i:s", $lot->getAttribute('date_time_close'))
        ]);
    }

    public function getById(int $id): ?Lot
    {
        return Lot::find($id);
    }

    public function findAll()
    {
        return Lot::all()->toArray();
    }

    public function findActiveLot(int $userId): ?Lot
    {
        $currentTimeStamp = time();
        return Lot::where('seller_id', $userId)
            ->where('date_time_open', '<', $currentTimeStamp)
            ->where('date_time_close', '>', $currentTimeStamp);
    }
}