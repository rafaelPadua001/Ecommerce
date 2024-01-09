<?php

namespace App\Providers\Payment;

use Illuminate\Support\ServiceProvider;
//use App\Models\Payment;
use App\Services\PaymentService\PaymentService;
use Illuminate\Contracts\Foundation\Application;

class PaymentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(PaymentService::class, function(Application $app){
            return new PaymentService(config('paymentService'));
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
