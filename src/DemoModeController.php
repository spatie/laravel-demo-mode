<?php

namespace Spatie\DemoMode;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class DemoModeController extends \Illuminate\Routing\Controller
{
    public function grantAccess(): RedirectResponse
    {
        session()->put('demo_access_route_visited', true);

        return new RedirectResponse(
            config('demo-mode.redirect_authorized_users_to_url')
        );
    }

    public function catchFallback(Request $request): RedirectResponse
    {
        if (!config('demo-mode.enabled')) {
            abort(404);
        }
        
        if (! (new DemoGuard())->hasDemoAccess($request)) {
            return new RedirectResponse(
                config('demo-mode.redirect_unauthorized_users_to_url')
            );
        }

        abort(404);
    }
}
