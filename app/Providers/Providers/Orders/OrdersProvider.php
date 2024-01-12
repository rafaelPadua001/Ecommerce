<?php

namespace App\Providers\Providers\Orders;

use Illuminate\Support\ServiceProvider;
use App\Services\OrderService\OrderService;
use Illuminate\Foundation\Application;

class OrdersProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(OrderService::class, function(Application $app){
            return new OrderService(config('orderService'));
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
