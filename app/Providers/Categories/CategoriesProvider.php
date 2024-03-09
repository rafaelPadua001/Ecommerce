<?php

namespace App\Providers\Categories;

use Illuminate\Support\ServiceProvider;
use App\Services\CategoryService\CategoryService;
use Illuminate\Contracts\Foundation\Application;

class CategoriesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CategoryService::class, function(Application $app){
            return new CategoryService(config('categoryService'));
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
