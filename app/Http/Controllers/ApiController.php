<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Request\AddLotRequest;
use App\Request\BuyLotRequest;
use Illuminate\Support\Facades\Auth;
use App\Response\Contracts\LotResponse;
use App\Service\Contracts\MarketService;
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
        $validator = Validator::make($request->all(), [
            'currency_id'       => 'required|integer|min:1',
            'date_time_open'    => 'required|integer|min:1',
            'date_time_close'   => 'required|integer|min:1',
            'price'             => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->returnFail($validator->errors()->first());
        }

        if (Auth::check()) {
            $this->marketService->addLot(
                new AddLotRequest(
                    $request->input('currency_id'),
                    Auth::id(),
                    $request->input('date_time_open'),
                    $request->input('date_time_close'),
                    $request->input('price')
                )
            );
            return response()->json(['Response: ' => 'Lot added!'], 201, ['Content-type' => 'application/json']);
        }
        return $this->forbiddenAccess();
    }

    public function buy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lot_id'    => 'required|integer|min:1',
            'amount'    => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->returnFail($validator->errors()->first());
        }

        if (Auth::check()) {
            $this->marketService->buyLot(
                new BuyLotRequest(Auth::id(), $request->input('lot_id'), $request->input('amount'))
            );
            return response()->json(['Response: ' => 'Trade created!'], 201, ['Content-type' => 'application/json']);
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
            return $this->returnFail($e->getMessage());
        }
        return response()->json($this->lotResponseToArray($lotResponse), 200, ['Content-type' => 'application/json']);
    }

    /**
     * Shared functionality for response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function returnFail(string $message) {
        return response()->json([
            'error' => [
                'message'       => $message,
                'status_code'   => 400
            ]
        ], 400, ['Content-Type' => 'application/json']);
    }

    /**
     * Shared functionality for response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function forbiddenAccess() {
        return response()->json([
            'error' => [
                'message'       => 'Forbidden Access!',
                'status_code'   => 403
            ]
        ], 403, ['Content-Type' => 'application/json']);
    }

    /**
     * Converter used in methods above.
     *
     * @param LotResponse $response
     * @return array
     */
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