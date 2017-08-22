<?php

namespace Spatie\DemoMode\Test;

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
}
