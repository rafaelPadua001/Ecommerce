<?php

namespace App\Providers\Address;

use Illuminate\Support\ServiceProvider;
use App\Services\AddressService\AddressService;
use Illuminate\Contracts\Foundation\Application;

class AddressProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(AddressService::class, function(Application $app){
            return new AddressService(config('addressService'));
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
