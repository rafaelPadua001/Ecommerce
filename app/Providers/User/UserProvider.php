<?php

namespace App\Providers\User;

use Illuminate\Support\ServiceProvider;
use App\Services\UserService\UserService;
use App\Models\User;
use Illuminate\Foundation\Application;

class UserProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(UserService::class, function(Application $app){
            return new UserService($app->make(User::class));
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
