<?php

namespace App\Service;

use App;
use Mail;
use App\Entity\Lot;
use App\Entity\Trade;
use App\Mail\TradeCreated;
use App\Response\Contracts\LotResponse;
use App\Request\Contracts\AddLotRequest;
use App\Request\Contracts\BuyLotRequest;
use App\Repository\Contracts\LotRepository;
use App\Repository\Contracts\UserRepository;
use App\Repository\Contracts\TradeRepository;
use App\Repository\Contracts\CurrencyRepository;
use App\Service\Contracts\MarketService as IMarketService;
use App\Exceptions\MarketException\LotDoesNotExistException;


class MarketService implements IMarketService
{
    private $lotRepository;
    private $userRepository;
    private $tradeRepository;
    private $currencyRepository;

    public function __construct(
        LotRepository $lotRepository,
        UserRepository $userRepository,
        TradeRepository $tradeRepository,
        CurrencyRepository $currencyRepository)
    {
        $this->lotRepository = $lotRepository;
        $this->userRepository = $userRepository;
        $this->tradeRepository = $tradeRepository;
        $this->currencyRepository = $currencyRepository;
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
                'date_time_open'    => date('Y-m-d H:i:s', $lotRequest->getDateTimeOpen()),
                'date_time_close'   => date('Y-m-d H:i:s', $lotRequest->getDateTimeClose()),
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
            throw new LotDoesNotExistException('Lot doesn\'t exist!');
        }
        return new App\Response\LotResponse($lot,
            $this->userRepository->getById($lot->getAttribute('seller_id')),
            $this->currencyRepository->getById($lot->getAttribute('currency_id')));
    }

    public function getLotList(): array
    {
        $result = [];
        $lotList = $this->lotRepository->findAll();
        foreach ($lotList as $lot) {
            $result[] = new App\Response\LotResponse($lot,
                $this->userRepository->getById($lot->getAttribute('seller_id')),
                $this->currencyRepository->getById($lot->getAttribute('currency_id')));
        }
        return $result;
    }
}