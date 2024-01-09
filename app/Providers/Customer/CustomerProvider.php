<?php

namespace App\Providers\Customer;

use Illuminate\Support\ServiceProvider;
use App\Services\CustomerService\CustomerService;
use Illuminate\Foundation\Application;

class CustomerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CustomerService::class, function(Application $app){
            return new CustomerService(config('customerService'));
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
