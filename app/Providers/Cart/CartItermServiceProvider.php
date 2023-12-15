<?php

namespace App\Providers\Cart;

use App\Models\CartItem;
use App\Models\Cart;
use App\Services\ShippmentService\ShippmentService;
use App\Services\CartService\CartItemService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;


class CartItermServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CartItemService::class, function(Application $app){
            return new CartItemService($app->make(CartItem::class), $app->make(Cart::class), $app->make(ShippmentService::class));
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
