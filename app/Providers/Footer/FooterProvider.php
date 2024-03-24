<?php

namespace App\Providers\Footer;

use Illuminate\Support\ServiceProvider;
use App\Services\FooterService\FooterService;
use Illuminate\Foundation\Application;
use App\Models\Footer;

class FooterProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(FooterService::class, function(Application $app){
            return new FooterService($app->make(Footer::class));
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
