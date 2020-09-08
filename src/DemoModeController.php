<?php

namespace Spatie\DemoMode;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        if (! config('demo-mode.enabled')) {
            abort(404);
        }

        if (! app(DemoGuard::class)->hasDemoAccess($request)) {
            return new RedirectResponse(
                config('demo-mode.redirect_unauthorized_users_to_url')
            );
        }

        abort(404);
    }
}
