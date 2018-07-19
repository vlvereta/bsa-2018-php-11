<?php

namespace Tests\Unit;

use Mail;
use App\User;
use App\Entity\Lot;
use Tests\TestCase;
use App\Entity\Trade;
use App\Mail\TradeCreated;
use App\Request\AddLotRequest;
use App\Request\BuyLotRequest;
use App\Service\MarketService;
use App\Response\Contracts\LotResponse;
use App\Service\Contracts\MarketService as IMarketService;
use App\Repository\{LotRepository, UserRepository, TradeRepository};

class MarketServiceTest extends TestCase
{
    private $lotRepository;
    private $userRepository;
    private $tradeRepository;

    private $marketService;

    protected function setUp()
    {
        parent::setUp();

        $this->lotRepository = $this->createMock(LotRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->tradeRepository = $this->createMock(TradeRepository::class);
        $this->marketService = new MarketService($this->lotRepository, $this->userRepository, $this->tradeRepository);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(IMarketService::class,
            $this->marketService);
    }

    /**
     * @test
     */
    public function createAddLotRequest()
    {
        $this->assertTrue(true);

        $request = $this->createMock(AddLotRequest::class);
        $request->method('getCurrencyId')->willReturn(2);
        $request->method('getSellerId')->willReturn(5);
        $request->method('getDateTimeOpen')->willReturn(1234567890);
        $request->method('getDateTimeClose')->willReturn(1234567899);
        $request->method('getPrice')->willReturn(23.76);
        return $request;
    }

    /**
     * @depends createAddLotRequest
     */
    public function testAddLotSuccess($request)
    {
        $this->lotRepository->method('add')->willReturn(new Lot([
            'price'             => $request->getPrice(),
            'seller_id'         => $request->getSellerId(),
            'currency_id'       => $request->getCurrencyId(),
            'date_time_open'    => $request->getDateTimeOpen(),
            'date_time_close'   => $request->getDateTimeClose(),
        ]));

        $lot = $this->marketService->addLot($request);

        $this->assertInstanceOf(Lot::class, $lot);

        $this->assertEquals($request->getPrice(), $lot->getAttribute('price'));
        $this->assertEquals($request->getSellerId(), $lot->getAttribute('seller_id'));
        $this->assertEquals($request->getCurrencyId(), $lot->getAttribute('currency_id'));
        $this->assertEquals($request->getDateTimeOpen(), $lot->getAttribute('date_time_open'));
        $this->assertEquals($request->getDateTimeClose(), $lot->getAttribute('date_time_close'));
    }

    /**
     * @test
     */
    public function createTestLot()
    {
        $this->assertTrue(true);

        $testLot = new Lot([
            'price'             => 1.12,
            'seller_id'         => 2,
            'currency_id'       => 3,
            'date_time_open'    => 1234567890,
            'date_time_close'   => 1234567899,
        ]);
        return $testLot;
    }

    /**
     * @test
     */
    public function createBuyLotRequest()
    {
        $this->assertTrue(true);

        $request = $this->createMock(BuyLotRequest::class);
        $request->method('getUserId')->willReturn(1);
        $request->method('getLotId')->willReturn(2);
        $request->method('getAmount')->willReturn(3.12);
        return $request;
    }

    /**
     * @depends createBuyLotRequest
     * @depends createTestLot
     */
    public function testBuyLot($request, $testLot)
    {
        Mail::fake();

        $testUser = new User([
            'name'      => 'Vladyslav',
            'email'     => 'example@test.com',
            'password'  => 'test'
        ]);
        $this->lotRepository->method('getById')->willReturn($testLot);
        $this->userRepository->method('getById')->willReturn($testUser);
        $this->tradeRepository->method('add')->willReturn(new Trade([
            'amount'    => $request->getAmount(),
            'lot_id'    => $request->getLotId(),
            'user_id'   => $request->getUserId(),
        ]));

        $trade = $this->marketService->buyLot($request);

        $this->assertInstanceOf(Trade::class, $trade);

        $this->assertEquals($request->getAmount(), $trade->getAttribute('amount'));
        $this->assertEquals($request->getLotId(), $trade->getAttribute('lot_id'));
        $this->assertEquals($request->getUserId(), $trade->getAttribute('user_id'));

        Mail::assertSent(TradeCreated::class);
    }

    /**
     * @expectedException \App\Exceptions\MarketException\LotDoesNotExistException
     */
    public function testGetLotFail()
    {
        $this->lotRepository->method('getById')->willReturn(null);
        $this->marketService->getLot(1);
    }

    /**
     * @depends createTestLot
     */
    public function testGetLotSuccess($testLot)
    {
        $this->lotRepository->method('getById')->willReturn($testLot);
        $lot = $this->marketService->getLot(1);
        $this->assertInstanceOf(LotResponse::class, $lot);
    }

    /**
     * @depends createTestLot
     */
    public function testGetLotList($testLot)
    {
        $this->lotRepository->method('findAll')->willReturn([$testLot, $testLot, $testLot]);
        $list = $this->marketService->getLotList();
        foreach ($list as $l) {
            $this->assertInstanceOf(LotResponse::class, $l);
            $this->assertEquals('1,12', $l->getPrice());
            $this->assertEquals(date('Y-m-d H:i:s', 1234567890), $l->getDateTimeOpen());
            $this->assertEquals(date('Y-m-d H:i:s', 1234567899), $l->getDateTimeClose());
        }
    }
}