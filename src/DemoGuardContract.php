<?php

namespace Spatie\DemoMode;

use Illuminate\Http\Request;

interface DemoGuardContract
{
    public function hasDemoAccess(Request $request): bool;
}
