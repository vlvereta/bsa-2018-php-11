<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use App\Repository\Contracts\{
    LotRepository as ILotRepository,
    UserRepository as IUserRepository,
    TradeRepository as ITradeRepository,
    MoneyRepository as IMoneyRepository,
    WalletRepository as IWalletRepository,
    CurrencyRepository as ICurrencyRepository
};
use App\Repository\{LotRepository, UserRepository, TradeRepository,
    MoneyRepository, WalletRepository, CurrencyRepository};

use App\Service\Contracts\{
    MarketService as IMarketService,
    WalletService as IWalletService,
    CurrencyService as ICurrencyService
};
use App\Service\{MarketService, WalletService, CurrencyService};

use App\Response\LotResponse;
use App\Response\Contracts\LotResponse as ILotResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Repository bindings!
         */
        $this->app->bind(ICurrencyRepository::class, function () {
            return new CurrencyRepository();
        });

        $this->app->bind(ILotRepository::class, function () {
            return new LotRepository();
        });

        $this->app->bind(IMoneyRepository::class, function () {
            return new MoneyRepository();
        });

        $this->app->bind(ITradeRepository::class, function () {
            return new TradeRepository();
        });

        $this->app->bind(IUserRepository::class, function () {
            return new UserRepository();
        });

        $this->app->bind(IWalletRepository::class, function () {
            return new WalletRepository();
        });

        /*
         * Service bindings!
         */
        $this->app->bind(ICurrencyService::class, function ($app) {
            return new CurrencyService(
                $app->make(ICurrencyRepository::class)
            );
        });

        $this->app->bind(IMarketService::class, function ($app) {
            return new MarketService(
                $app->make(ILotRepository::class),
                $app->make(IUserRepository::class),
                $app->make(ITradeRepository::class)
            );
        });

        $this->app->bind(IWalletService::class, function ($app) {
            return new WalletService(
                $app->make(IMoneyRepository::class),
                $app->make(IWalletRepository::class)
            );
        });

        /*
         * LotResponse binding!
         */
        $this->app->bind(ILotResponse::class, function ($app, $lot) {
           return new LotResponse($lot);
        });
    }
}