<?php

namespace App\Service;

use App\Entity\Lot;
use App\Entity\Trade;
use App\Mail\TradeCreated;
use App\Response\Contracts\LotResponse;
use App\Request\Contracts\AddLotRequest;
use App\Request\Contracts\BuyLotRequest;
use App\Repository\Contracts\LotRepository;
use App\Repository\Contracts\UserRepository;
use App\Repository\Contracts\TradeRepository;
use App\Service\Contracts\MarketService as IMarketService;
use App\Exceptions\MarketException\LotDoesNotExistException;


class MarketService implements IMarketService
{
    private $lotRepository;
    private $userRepository;
    private $tradeRepository;

    public function __construct(
        LotRepository $lotRepository,
        UserRepository $userRepository,
        TradeRepository $tradeRepository)
    {
        $this->lotRepository = $lotRepository;
        $this->tradeRepository = $tradeRepository;
    }

    public function addLot(AddLotRequest $lotRequest): Lot
    {
        /*
         * Добавить проверки и выброс исключений соглассно заданию.
         */
        return $this->lotRepository->add(
            new Lot([
                'price'             => $lotRequest->getPrice(),
                'seller_id'         => $lotRequest->getSellerId(),
                'currency_id'       => $lotRequest->getCurrencyId(),
                'date_time_open'    => $lotRequest->getDateTimeOpen(),
                'date_time_close'   => $lotRequest->getDateTimeClose(),
            ])
        );
    }

    public function buyLot(BuyLotRequest $lotRequest): Trade
    {
        /*
         * Добавить проверки и выброс исключений соглассно заданию.
         */
        $lot = $this->lotRepository->getById($lotRequest->getLotId());
        $trade = $this->tradeRepository->add(
            new Trade([
                'amount'    => $lotRequest->getAmount(),
                'lot_id'    => $lotRequest->getLotId(),
                'user_id'   => $lotRequest->getUserId(),
            ])
        );
        Mail::to($this->userRepository->getById($lot->getAttribute('seller_id')))->send(new TradeCreated($trade));
        return $trade;
    }

    public function getLot(int $id): LotResponse
    {
        $lot = $this->lotRepository->getById($id);
        if (!$lot) {
            throw new LotDoesNotExistException('Lot doesn\'t exists!');
        }
        return App::make(LotResponse::class, $lot);
    }

    public function getLotList(): array
    {
        $result = [];
        $lotList = $this->lotRepository->findAll();
        foreach ($lotList as $lot) {
            $result[] = App::make(LotResponse::class, $lot);
        }
        return $result;
    }
}