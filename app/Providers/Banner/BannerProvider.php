<?php

namespace App\Providers\Banner;

use Illuminate\Support\ServiceProvider;
use App\Services\BannerService\BannerService;
use Illuminate\Foundation\Application;

class BannerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(BannerService::class, function(Application $app){
            return new BannerService(config('bannerService'));
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
