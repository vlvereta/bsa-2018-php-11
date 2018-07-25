<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Request\AddLotRequest;
use App\Service\Contracts\MarketService;

class WebController extends Controller
{
    private $marketService;

    public function __construct()
    {
        $this->marketService = app()->make(MarketService::class);
    }

    public function add()
    {
        return view('add-form', ['error' => null]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency_id'       => 'required|integer|min:1',
            'seller_id'         => 'required|integer|min:1',
            'date_time_open'    => 'required|integer|min:1',
            'date_time_close'   => 'required|integer|min:1',
            'price'             => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->returnFail($validator->errors()->first());
        }
        try {
            $this->marketService->addLot(
                new AddLotRequest(
                    $request->input('currency_id'),
                    $request->input('seller_id'),
                    $request->input('date_time_open'),
                    $request->input('date_time_close'),
                    $request->input('price')
            ));
        } catch (\Exception $e) {
            return $this->returnFail($e->getMessage());
        }
        return view('add-form', ['error' => false]);
    }

    private function returnFail(string $message)
    {
        return view('add-form', [
            'error' => true,
            'message' => $message
        ]);
    }
}