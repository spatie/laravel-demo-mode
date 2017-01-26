<?php

namespace Spatie\DemoMode;

use Illuminate\Http\RedirectResponse;

class DemoModeController extends \Illuminate\Routing\Controller
{
    /**
     * Handle demo mode
     * 
     * @param void
     * @return RedirectResponse
     */
    public function handle()
    {
        session()->put('demo_access_granted', true);

        return new RedirectResponse(config('laravel-demo-mode.redirect_authorized_users_to_url'));
    }
}
