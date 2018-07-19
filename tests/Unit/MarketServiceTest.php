<?php

namespace Tests\Unit;

use App\Entity\Lot;
use App\Request\AddLotRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Response\Contracts\LotResponse;
use App\Service\Contracts\MarketService as IMarketService;

class MarketServiceTest extends TestCase
{
    private $marketService;

    protected function setUp()
    {
        parent::setUp();

        $this->marketService = $this->app->make(IMarketService::class);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(IMarketService::class,
            $this->marketService);
    }

    public function testAddLot()
    {
        $request = new AddLotRequest(1, 2, 1532026944, 1532026999, 23.012);
        $lot = $this->marketService->addLot($request);
        $this->assertInstanceOf(Lot::class, $lot);
    }

//    public function testBuyLot()
//    {
//
//    }
//
//    public function testGetLot()
//    {
//
//    }
//
//    public function testGetLotList()
//    {
//
//        $list = $this->marketService->getLotList();
//        foreach ($list as $l) {
//            $this->assertInstanceOf(LotResponse::class, $l);
//        }
//    }
}
