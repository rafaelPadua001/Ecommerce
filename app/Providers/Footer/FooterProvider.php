<?php

namespace App\Providers\Footer;

use Illuminate\Support\ServiceProvider;
use App\Services\FooterService\FooterService;
use Illuminate\Foundation\Application;

class FooterProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(FooterService::class, function(Application $app){
            return new FooterService(config('footerService'));
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
