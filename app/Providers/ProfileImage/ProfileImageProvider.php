<?php

namespace App\Providers\ProfileImage;

use Illuminate\Support\ServiceProvider;
use App\Services\ProfileImageService\ProfileImageService;
use Illuminate\Contracts\Foundation\Application;

class ProfileImageProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(ProfileImageService::class, function(Application $app){
            return new ProfileImageService(config('profileImageService'));
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
