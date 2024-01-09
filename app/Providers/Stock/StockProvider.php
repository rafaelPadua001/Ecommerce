<?php

namespace App\Providers\Stock;

use Illuminate\Support\ServiceProvider;
use App\Services\StockService\StockService;
use App\Models\ProductStock;
use Illuminate\Foundation\Application;

class StockProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(StockService::class, function(Application $app){
            return new StockService($app->make(ProductStock::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
