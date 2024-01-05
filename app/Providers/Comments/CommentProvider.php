<?php

namespace App\Providers\Comments;

use Illuminate\Support\ServiceProvider;
use App\Services\CommentService\CommentService;
use Illuminate\Foundation\Application;

class CommentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CommentService::class, function(Application $app){
            return new CommentService(config('commentService'));
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
