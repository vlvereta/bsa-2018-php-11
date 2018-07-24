<?php

namespace Tests\Feature;

use App\Entity\Money;
use App\User;
use Tests\TestCase;

class ApiTest extends TestCase
{
    public function testAddSuccess()
    {
        /**
         * Seeding database by 7 Users->Wallets->Money->Currencies!
         * Each model's id equal to another model's id. Relationship saved.
        */
        factory(Money::class, 7)->create();

        $user = User::find(4);

        $response = $this->actingAs($user)->json('post', '/api/v1/lots', [
            'currency_id'       => 4,
            'date_time_open'    => 1234567890,
            'date_time_close'   => 1234567899,
            'price'             => 64.87
        ]);
        $response
            ->assertStatus(201)
            ->assertHeader('Content-type', 'application/json')
            ->assertJson(['Response: ' => 'Lot added!']);
    }

    public function testAddFail()
    {
        $response = $this->json('post', '/api/v1/lots', [
            'currency_id'       => 3,
            'date_time_open'    => 1234567890,
            'date_time_close'   => 1234567899,
            'price'             => 64.87
        ]);
        $response
            ->assertStatus(403)
            ->assertHeader('Content-type', 'application/json')
            ->assertJsonStructure([
                'error' => [
                    'message',
                    'status_code'
                ]
            ]);
    }

    public function testBuySuccess()
    {
        $user = User::find(4);

        $request = $this->actingAs($user)->json('post', '/api/v1/trade', [
            'lot_id'    => 1,
            'amount'    => 243.56
        ]);
        $request
            ->assertStatus(201)
            ->assertHeader('Content-type', 'application/json')
            ->assertJson(['Response: ' => 'Trade created!']);
    }

    public function testBuyFail()
    {
        $response = $this->json('post', '/api/v1/trade', [
            'lot_id'    => 1,
            'amount'    => 243.56
        ]);
        $response
            ->assertStatus(403)
            ->assertHeader('Content-type', 'application/json')
            ->assertJsonStructure([
                'error' => [
                    'message',
                    'status_code'
                ]
            ]);
    }

    public function testList()
    {
        $response = $this->json('get', '/api/v1/lots/');
        $response
            ->assertStatus(200)
            ->assertHeader('Content-type', 'application/json');
    }

    public function testShowSuccess()
    {
        $response = $this->json('get', '/api/v1/lots/1');
        $response
            ->assertStatus(200)
            ->assertHeader('Content-type', 'application/json')
            ->assertJsonStructure([
                'id',
                'user',
                'currency',
                'amount',
                'date_time_open',
                'date_time_close',
                'price'
            ]);
    }

    public function testShowFail()
    {
        $response = $this->json('get', '/api/v1/lots/99999');
        $response
            ->assertStatus(400)
            ->assertHeader('Content-type', 'application/json')
            ->assertJsonStructure([
                'error' => [
                    'message',
                    'status_code'
                ]
            ]);
    }
}