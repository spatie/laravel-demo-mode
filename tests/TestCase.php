<?php

namespace Spatie\DemoMode\Test;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase as Orchestra;
use Route;
use Spatie\DemoMode\DemoModeServiceProvider;
use Spatie\DemoMode\DemoMode;
use PHPUnit_Framework_Assert as PHPUnit;

class TestCase extends Orchestra
{
    /**
     * @var array
     */
    protected $config;

    public function setUp()
    {
        parent::setUp();

        $this->registerMiddleWare();

        $this->setUpRoutes($this->app);

        $this->config = $this->app['config']->get('laravel-demo-mode');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('laravel-demo-mode.redirect_authorized_users_to_url', '/secret-page');
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
        $this->app[Router::class]->middleware('demoMode', DemoMode::class);
    }

    /**
     * @param Application $app
     */
    protected function setUpRoutes($app)
    {
        Route::any('/secret-page', ['middleware' => 'demoMode', function () {
            return 'secret content';
        }]);

        Route::any('/unprotected-page', function () {
            return 'unprotected content';
        });

        Route::any('/work-in-progress', function () {
            return 'this site is not launched yet';
        });
    }

    /**
     * Assert whether the client was redirected to a given URI.
     *
     * @param  string $uri
     * @param  array $with
     *
     * @return void
     */
    public function assertRedirectedTo($uri, $with = [])
    {
        PHPUnit::assertInstanceOf('Illuminate\Http\RedirectResponse', $this->response);

        PHPUnit::assertEquals(str_replace('http://localhost', '', $this->app['url']->to($uri)),
            $this->response->headers->get('Location'));
    }
}
