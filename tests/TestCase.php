<?php

namespace Spatie\DemoMode\Test;

use Illuminate\Foundation\Application;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase as Orchestra;
use Route;
use Spatie\DemoMode\DemoModeServiceProvider;
use Spatie\DemoMode\DemoMode;

class TestCase extends Orchestra
{
    /** @var array */
    protected $config = [];

    public function setUp()
    {
        parent::setUp();

        $this->registerMiddleWare();

        $this->setUpRoutes($this->app);

        $this->registerFallbackRoute();

        $this->config = $this->app['config']->get('demo-mode');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('demo-mode.redirect_authorized_users_to_url', '/secret-page');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            DemoModeServiceProvider::class,
        ];
    }

    protected function registerMiddleware()
    {
        $this->app[Router::class]->aliasMiddleware('demoMode', DemoMode::class);
    }

    /**
     * @param Application $app
     */
    protected function setUpRoutes($app)
    {
        $this->app->get('router')->setRoutes(new RouteCollection());

        Route::demoAccess('/demo');

        Route::any('/secret-page', ['middleware' => 'demoMode', function () {
            return 'secret content';
        }]);

        Route::any('/unprotected-page', function () {
            return 'unprotected content';
        });

        Route::any('/under-construction', function () {
            return 'this site is not launched yet';
        });
    }

    protected function registerFallbackRoute()
    {
        $this->app[Router::class]->fallback('\Spatie\DemoMode\DemoModeController@catchFallback');
    }
}
