<?php

namespace Spatie\DemoMode;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DemoMode
{
    /** @var array */
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config->get('demo-mode');
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
        if (! $this->config['enabled']) {
            return $next($request);
        }

        if ($this->protectedByDemoMode($request)) {
            if (! app(DemoGuard::class)->hasDemoAccess($request)) {
                return new RedirectResponse($this->config['redirect_unauthorized_users_to_url']);
            }
        }

        return $next($request);
    }

    protected function protectedByDemoMode(Request $request): bool
    {
        return true;
    }
}
