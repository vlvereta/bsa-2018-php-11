<?php

namespace App\Http\Controllers;

use App\Request\AddLotRequest;
use App\Request\BuyLotRequest;
use App\Response\Contracts\LotResponse;
use App\Service\Contracts\MarketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\MarketException\LotDoesNotExistException;

class ApiController extends Controller
{
    private $marketService;

    public function __construct()
    {
        $this->marketService = app()->make(MarketService::class);
    }

    public function add(Request $request)
    {
        if (Auth::check()) {
            $lot = $this->marketService->addLot(
                new AddLotRequest(
                    $request->input('currency_id'),
                    Auth::id(),
                    $request->input('date_time_open'),
                    $request->input('date_time_close'),
                    $request->input('price')
                )
            );
            return redirect()->route('show', ['id' => $lot->getAttribute('id')]);
        }
        return $this->forbiddenAccess();
    }

    public function buy(Request $request)
    {
        if (Auth::check()) {
            $trade = $this->marketService->buyLot(
                new BuyLotRequest(Auth::id(), $request->input('lot_id'), $request->input('amount'))
            );
            return redirect()->route('show', ['id' => $trade->getAttribute('lot_id')]);
        }
        return $this->forbiddenAccess();
    }

    public function list() {
        $resultList = [];
        $list = $this->marketService->getLotList();
        foreach ($list as $l) {
            $resultList[] = $this->lotResponseToArray($l);
        }
        return response()->json($resultList, 200, ['Content-type' => 'application/json']);
    }

    public function show(int $id) {
        try {
            $lotResponse = $this->marketService->getLot($id);
        } catch (LotDoesNotExistException $e) {
            return response()->json([
                'error' => [
                    'message'       => $e->getMessage(),
                    'status_code'   => 400
                ]
            ], 400, ['Content-Type' => 'application/json']);
        }
        return response()->json($this->lotResponseToArray($lotResponse), 200, ['Content-type' => 'application/json']);
    }

    private function forbiddenAccess() {
        return response()->json([
            'error' => [
                'message'       => 'Forbidden Access!',
                'status_code'   => 403
            ]
        ], 403, ['Content-Type' => 'application/json']);
    }

    private function lotResponseToArray(LotResponse $response): array
    {
        return [
            'id'                => $response->getId(),
            'user'              => $response->getUserName(),
            'currency'          => $response->getCurrencyName(),
            'amount'            => $response->getAmount(),
            'date_time_open'    => $response->getDateTimeOpen(),
            'date_time_close'   => $response->getDateTimeClose(),
            'price'             => $response->getPrice(),
        ];
    }
}