<?php

namespace Spatie\DemoMode\Test;

use Illuminate\Foundation\Testing\TestResponse;

class DemoModeTest extends TestCase
{
    /** @test */
    public function it_redirects_users_who_have_not_been_granted_access_to_a_work_in_progress_page()
    {
        $this->disableExceptionHandling();

        $this->assertCannotVisitSecretPage();
    }

    /** @test */
    public function it_only_redirects_users_if_demo_mode_is_enabled()
    {
        $this->app['config']->set('demo-mode.enabled', false);

        $this->assertCanVisitSecretPage();
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
    public function it_will_not_intervene_with_unprotected_routes()
    {
        $this
            ->get('/unprotected-page')
            ->assertSee('unprotected content');
    }

    /** @test */
    public function it_will_automatically_authorize_configured_ips()
    {
        $authorizedIps = ['192.0.2.1', '192.0.2.2', '192.0.2.3'];

        $this->assertCannotVisitSecretPage();

        $this->app['config']->set('demo-mode.authorized_ips', $authorizedIps);

        $this->assertCannotVisitSecretPage($authorizedIps[0]);
    }

    /** @test */
    public function it_will_not_create_the_demo_route_in_strict_mode()
    {
        $this->app['config']->set('demo-mode.strict_mode', true);

        $this->setUpRoutes($this->app);

        $this
            ->get('/demo')
            ->assertStatus(404);
    }

    /** @test */
    public function it_will_only_authorize_configured_ips_with_strict_mode_enabled()
    {
        $this->app['config']->set('demo-mode.strict_mode', true);

        $this->setUpRoutes($this->app);

        $this->assertCannotVisitSecretPage();

        $authorizedIps = ['192.0.2.1', '192.0.2.2', '192.0.2.3'];

        $this->app['config']->set('demo-mode.authorized_ips', $authorizedIps);

        $this->assertCannotVisitSecretPage($authorizedIps[0]);
    }

    /** @test */
    public function it_redirects_users_who_have_not_been_granted_demo_access_when_accessing_an_unknown_route()
    {
        $this
            ->get('/unknown-page')
            ->assertRedirect()
            ->assertHeader('location', $this->config['redirect_unauthorized_users_to_url']);
    }

    protected function getWithIp(string $uri, string $ip): TestResponse
    {
        return $this->call('GET', $uri, [], [], [], ['REMOTE_ADDR' => $ip]);
    }

    protected function assertCanVisitSecretPage(string $ip = '127.0.0.1')
    {
        $this->getWithIp('/secret-page', $ip)
            ->assertStatus(200)
            ->assertSee('secret content');
    }

    protected function assertCannotVisitSecretPage()
    {
        $this
            ->get('/secret-page')
            ->assertRedirect()
            ->assertHeader('location', $this->config['redirect_unauthorized_users_to_url']);
    }
}
