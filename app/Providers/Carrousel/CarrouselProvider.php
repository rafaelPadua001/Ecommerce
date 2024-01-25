<?php

namespace App\Providers\Carrousel;

use Illuminate\Support\ServiceProvider;
use App\Services\CarrouselService\CarrouselService;
use Illuminate\Foundation\Application;

class CarrouselProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CarrouselService::class, function(Application $app){
            return new CarrouselService(config('carrouselService'));
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
