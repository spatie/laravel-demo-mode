<?php

namespace Spatie\DemoMode;

use Illuminate\Http\Request;

class DefaultDemoGuard implements DemoGuard
{
    public function hasDemoAccess(Request $request): bool
    {
        if ($this->isIpAuthorized($request) || auth()->check()) {
            return true;
        }

        return $this->demoRouteEnabled() && session()->has('demo_access_route_visited');
    }

    protected function demoRouteEnabled(): bool
    {
        return ! config('demo-mode.strict_mode');
    }

    protected function isIpAuthorized(Request $request): bool
    {
        return in_array($request->ip(), config('demo-mode.authorized_ips'));
    }
}
