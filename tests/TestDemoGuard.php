<?php

namespace Spatie\DemoMode\Test;

use Illuminate\Http\Request;
use Spatie\DemoMode\DemoGuardContract;

class TestDemoGuard implements DemoGuardContract
{
    public $flag = false;

    public function hasDemoAccess(Request $request): bool
    {
        return $this->flag;
    }
}
