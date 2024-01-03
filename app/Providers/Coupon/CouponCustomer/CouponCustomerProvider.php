<?php

namespace App\Providers\Coupon\CouponCustomer;

use Illuminate\Support\ServiceProvider;
use App\Services\CouponService\CouponCustomer\CouponCustomerService;
use Illuminate\Foundation\Application;

class CouponCustomerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CouponCustomerService::class, function(Application $app){
            return new CouponCustomerService(config('couponCustomerService'));
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
