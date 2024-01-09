<?php

namespace App\Providers\Coupon;

use Illuminate\Support\ServiceProvider;
use App\Services\CouponService\CouponService;
use Illuminate\Foundation\Application;

class CouponProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CouponService::class, function(Application $app){
            return new CouponService(config('couponService'));
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
