<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Http\Middleware\AdminMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        
        // Set a lower memory limit for debug mode
        if (env('APP_DEBUG') === true) {
            ini_set('memory_limit', '256M');
        }
        
        // Register admin middleware
        $this->app['router']->aliasMiddleware('admin', AdminMiddleware::class);
    }
}
