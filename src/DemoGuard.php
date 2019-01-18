<?php

namespace Spatie\DemoMode;

use Illuminate\Http\Request;

interface DemoGuard
{
    public function hasDemoAccess(Request $request): bool;
}
