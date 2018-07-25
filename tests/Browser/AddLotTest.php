<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddLotTest extends DuskTestCase
{
    public function testAddLotSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/market/lots/add')
                ->type('currency_id', 1)
                ->type('seller_id', 2)
                ->type('date_time_open', 1234567890)
                ->type('date_time_close', 1234567899)
                ->type('price', 124.3)
                ->press('Add')
                ->pause(1000)
                ->assertSee('Lot has been added successfully!');
        });
    }

    public function testAddLotFail() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/market/lots/add')
                ->type('currency_id', 1)
                ->type('seller_id', 2)
                ->type('date_time_open', 1234567890)
                ->type('date_time_close', 1234567899)
                ->type('price', 'Hello, World')
                ->press('Add')
                ->pause(1000)
                ->assertSee('Sorry, error has been occurred: The price must be a number.');
        });
    }
}