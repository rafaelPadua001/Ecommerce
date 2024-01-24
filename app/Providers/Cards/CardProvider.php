<?php

namespace App\Providers\Cards;

use Illuminate\Support\ServiceProvider;
use App\Services\CardService\CardService;
use Illuminate\Foundation\Application;

class CardProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CardService::class, function(Application $app){
            return new CardService(config('cardService'));
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
