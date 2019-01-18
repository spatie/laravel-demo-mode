<?php

namespace Spatie\DemoMode\Test;

use Spatie\DemoMode\Test\Concerns\SharesTests;

class DefaultDemoGuardTest extends TestCase
{
    use SharesTests;

    /** @test */
    public function it_redirects_users_who_have_not_been_granted_access_to_a_work_in_progress_page()
    {
        $this->withoutExceptionHandling();

        $this->assertCannotVisitSecretPage();
    }

    /** @test */
    public function it_will_automatically_authorize_configured_ips()
    {
        $authorizedIps = ['192.0.2.1', '192.0.2.2', '192.0.2.3'];

        $this->assertCannotVisitSecretPage();

        $this->app['config']->set('demo-mode.authorized_ips', $authorizedIps);

        $this->assertCanVisitSecretPage($authorizedIps[0]);
    }

    /** @test */
    public function it_will_only_authorize_configured_ips_with_strict_mode_enabled()
    {
        $this->app['config']->set('demo-mode.strict_mode', true);

        $this->setUpRoutes($this->app);

        $this->assertCannotVisitSecretPage();

        $authorizedIps = ['192.0.2.1', '192.0.2.2', '192.0.2.3'];

        $this->app['config']->set('demo-mode.authorized_ips', $authorizedIps);

        $this->assertCanVisitSecretPage($authorizedIps[0]);
    }

    /** @test */
    public function it_will_allow_visiting_secret_pages_after_having_visited_the_grant_demo_url_first()
    {
        $this
            ->get('/demo')
            ->assertRedirect()
            ->assertHeader('location', $this->config['redirect_authorized_users_to_url']);

        $this->assertCanVisitSecretPage();
    }

    /** @test */
    public function it_redirects_users_who_have_not_been_granted_demo_access_when_accessing_an_unknown_route()
    {
        $this
            ->get('/unknown-page')
            ->assertRedirect()
            ->assertHeader('location', $this->config['redirect_unauthorized_users_to_url']);
    }
}
