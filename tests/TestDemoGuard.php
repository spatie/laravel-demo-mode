<?php

namespace Spatie\DemoMode\Test;

use Illuminate\Http\Request;
use Spatie\DemoMode\DemoGuard;

class TestDemoGuard implements DemoGuard
{
    public $flag = false;

    public function hasDemoAccess(Request $request): bool
    {
        return $this->flag;
    }
}
