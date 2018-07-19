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
            'date_time_open'    => $lot->getAttribute('date_time_open'),
            'date_time_close'   => $lot->getAttribute('date_time_close')
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

    /*
     * Что конкретно имеется ввиду? Юзер может открыть только один лот?..
     */
    public function findActiveLot(int $userId): ?Lot
    {
        // TODO: Implement findActiveLot() method.
    }
}