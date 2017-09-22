<?php

namespace Spatie\DemoMode\Test;

use Illuminate\Routing\RouteCollection;

class DemoModeTest extends TestCase
{
    /** @test */
    public function it_redirects_users_who_have_not_been_granted_access_to_a_work_in_progress_page()
    {
        $this
            ->get('/secret-page')
            ->assertRedirect()
            ->assertHeader('location', $this->config['redirect_unauthorized_users_to_url']);
    }

    /** @test */
    public function it_only_redirects_users_if_demo_mode_is_enabled()
    {
        $this->app['config']->set('demo-mode.enabled', false);

        $this->get('/secret-page')->assertStatus(200);
    }

    /** @test */
    public function it_will_allow_visiting_secret_pages_after_having_visited_the_grant_demo_url_first()
    {
        $this
            ->get('/demo')
            ->assertRedirect()
            ->assertHeader('location', $this->config['redirect_authorized_users_to_url']);
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

        $this
            ->get('/secret-page')
            ->assertRedirect();

        $this->app['config']->set('demo-mode.authorized_ips', $authorizedIps);

        $this
            ->call('GET', '/secret-page', [], [], [], ['REMOTE_ADDR' => $authorizedIps[0]])
            ->assertSee('secret content');
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

        $this
            ->get('/secret-page')
            ->assertRedirect()
            ->assertHeader('location', $this->config['redirect_unauthorized_users_to_url']);

        $authorizedIps = ['192.0.2.1', '192.0.2.2', '192.0.2.3'];

        $this->app['config']->set('demo-mode.authorized_ips', $authorizedIps);

        $this
            ->call('GET', '/secret-page', [], [], [], ['REMOTE_ADDR' => $authorizedIps[0]])
            ->assertSee('secret content');
    }
}
