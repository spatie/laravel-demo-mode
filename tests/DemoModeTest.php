<?php

namespace Spatie\DemoMode\Test;

class DemoModeTest extends TestCase
{
    /** @test */
    public function it_redirects_users_who_have_not_been_granted_access_to_a_work_in_progress_page()
    {
        $this->call('GET', '/secret-page');

        $this->assertRedirectedTo($this->config['redirect_unauthorized_users_to_url']);
    }

    /** @test */
    public function it_will_allow_visiting_secret_pages_after_having_visited_the_grant_demo_url_first()
    {
        $this->call('GET', '/demo');

        $this->assertRedirectedTo($this->config['redirect_authorized_users_to_url']);
    }

    /** @test */
    public function it_will_not_intervene_with_unprotected_routes()
    {
        $this->call('GET', '/unprotected-page');

        $this->see('unprotected content');
    }
}
