<?php

namespace Spatie\DemoMode;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectIfDemo
{
    /**
     * @var array
     */
    private $config;

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
        if ($this->protectedByDemoMode($request)) {
            if (!$this->hasDemoAccess($request)) {
                return new RedirectResponse($this->config['redirect_unauthorized_users_to_url']);
            }
        }

        return $next($request);
    }

    protected function protectedByDemoMode(Request $request) : bool
    {
        return $this->config['enabled'];
    }

    protected function hasDemoAccess($request) : bool
    {
        if (session()->has('demo_access_granted')) {
            return true;
        }

        if (auth()->user()) {
            return true;
        }

        return false;
    }
}
