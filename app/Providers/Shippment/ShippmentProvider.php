<?php

namespace App\Providers\Shippment;

use App\Models\Shippment;
use Illuminate\Support\ServiceProvider;
use App\Services\ShippmentService\ShippmentService;
use Illuminate\Contracts\Foundation\Application;

class ShippmentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(ShippmentService::class, function(Application $app){
            return new ShippmentService($app->make(Shippment::class));
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
