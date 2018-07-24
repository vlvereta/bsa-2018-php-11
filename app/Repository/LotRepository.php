<?php

namespace App\Repository;

use App\Entity\Lot;
use App\Repository\Contracts\LotRepository as ILotRepository;

class LotRepository implements ILotRepository
{
    public function add(Lot $lot): Lot
    {
        $lot->save();
        return $lot;
    }

    public function getById(int $id): ?Lot
    {
        return Lot::find($id)->first();
    }

    public function findAll()
    {
        return Lot::all();
    }

    public function findActiveLot(int $userId): ?Lot
    {
        $currentTimeStamp = time();
        return Lot::where('seller_id', $userId)
            ->where('date_time_open', '<', $currentTimeStamp)
            ->where('date_time_close', '>', $currentTimeStamp);
    }
}