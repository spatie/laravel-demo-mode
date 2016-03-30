<?php

namespace Spatie\DemoMode;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ServiceProvider;

class DemoModeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-demo-mode.php' => config_path('laravel-demo-mode.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-demo-mode.php', 'laravel-demo-mode');

        $router = $this->app['router'];

        $router->macro('demoAccess', function ($url) use ($router) {

            $router->get($url, function () {
                session()->put('demo_access_granted', true);

                return new RedirectResponse(config('laravel-demo-mode.redirect_authorized_users_to_url'));
            });
        });
    }
}
