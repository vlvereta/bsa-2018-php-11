<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class Task3Test extends DuskTestCase
{
    /**
     * Test adding Lot.
     *
     * @return void
     */
    public function testAddLotSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/market/lots/add')
                ->type('currencyId', 1)
                ->type('sellerId', 2)
                ->type('dateTimeOpen', 1234567890)
                ->type('dateTimeClose', 1234567899)
                ->type('price', 124.3)
                ->press('Add')
                ->pause(1000)
                ->assertSee('Lot has been added successfully!');
        });
    }
}
