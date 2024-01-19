<?php

namespace App\Providers\Store;

use Illuminate\Support\ServiceProvider;
use App\Services\StoreService\StoreService;
use Illuminate\Foundation\Application;

class StoreProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(StoreService::class, function(Application $app){
            return new StoreService(config('storeService'));
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
