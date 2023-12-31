<?php

namespace App\Providers\Cart;

use App\Models\Cart;
use App\Services\CartService\CartService;
use App\Services\CartService\CartItemService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CartService::class, function(Application $app){
           return new CartService($app->make(Cart::class), $app->make(CartItemService::class));
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
