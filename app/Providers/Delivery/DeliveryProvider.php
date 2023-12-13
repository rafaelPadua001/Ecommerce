<?php

namespace App\Providers\Delivery;


use App\Models\Delivery;
use App\Services\DeliveryService\DeliveryService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class DeliveryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(DeliveryService ::class, function(Application $app){
            return new DeliveryService($app->make(Delivery::class));
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
