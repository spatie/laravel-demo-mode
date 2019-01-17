<?php

namespace Spatie\DemoMode\Test;

use Spatie\DemoMode\DemoGuardContract;

class CustomDemoGuardTest extends TestCase
{
    use TestsShared;

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']['demo-mode.guard'] = TestDemoGuard::class;
    }

    /** @test */
    public function it_will_allow_visiting_secret_pages_if_the_custom_flag_is_set_to_true()
    {
        $this->turnFlagOn()
            ->assertCanVisitSecretPage();
    }

    /** @test */
    public function it_will_restrict_access_to_secret_pages_if_the_custom_flag_is_set_to_false()
    {
        $this->turnFlagOff()
            ->assertCannotVisitSecretPage();
    }

    /** @test */
    public function it_redirects_users_when_accessing_an_unknown_route_if_the_custom_flag_is_off()
    {
        $this
            ->turnFlagOff()
            ->get('/unknown-page')
            ->assertRedirect()
            ->assertHeader('location', $this->config['redirect_unauthorized_users_to_url']);
    }

    private function turnFlagOn()
    {
        $this->app->get(DemoGuardContract::class)->flag = true;

        return $this;
    }

    private function turnFlagOff()
    {
        $this->app->get(DemoGuardContract::class)->flag = false;

        return $this;
    }
}
