<?php

namespace Spatie\DemoMode;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Route as RouteFacade;

class DemoMode
{
    /** @var array */
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config->get('laravel-demo-mode');
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->protectedByDemoMode(RouteFacade::current()) && !$this->hasDemoAccess($request)) {
            return new RedirectResponse($this->config['redirect_unauthorized_users_to_url']);
        }

        return $next($request);
    }

    protected function protectedByDemoMode(Route $currentRoute) : bool
    {
        $currentRouteMiddlewares = RouteFacade::gatherRouteMiddlewares($currentRoute);
        return in_array('Spatie\DemoMode\DemoMode', $currentRouteMiddlewares);
    }

    protected function hasDemoAccess(Request $request) : bool
    {
        return session()->has('demo_access_granted') || auth()->check();
    }
}
