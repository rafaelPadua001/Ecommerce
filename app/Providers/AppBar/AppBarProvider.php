<?php

namespace App\Providers\AppBar;

use Illuminate\Support\ServiceProvider;
use App\Services\AppBarService\AppBarService;
use Illuminate\Foundation\Application;

class AppBarProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(AppBarService::class, function(Application $app){
            return new AppBarService(config('appBarService'));
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
