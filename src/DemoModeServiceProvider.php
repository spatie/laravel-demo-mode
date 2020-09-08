<?php

namespace Spatie\DemoMode;

use Illuminate\Support\ServiceProvider;

class DemoModeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/demo-mode.php' => config_path('demo-mode.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/demo-mode.php', 'demo-mode');

        $router = $this->app['router'];

        $router->macro('demoAccess', function ($url) use ($router) {
            if (! config('demo-mode.enabled') || config('demo-mode.strict_mode')) {
                return;
            }

            $router->get($url, '\Spatie\DemoMode\DemoModeController@grantAccess');
        });

        $router->fallback('\Spatie\DemoMode\DemoModeController@catchFallback');

        $this->app->singleton(DemoGuard::class, config('demo-mode.guard'));
    }
}
