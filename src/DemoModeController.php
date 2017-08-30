<?php

namespace Spatie\DemoMode;

use Illuminate\Http\RedirectResponse;

class DemoModeController extends \Illuminate\Routing\Controller
{
    public function grantAccess(): RedirectResponse
    {
        session()->put('demo_access_granted', true);

        return new RedirectResponse(
            config('demo-mode.redirect_authorized_users_to_url')
        );
    }
}
