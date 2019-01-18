<?php

namespace Spatie\DemoMode\Test\Concerns;

use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\TestResponse;

trait SharesTests
{
    use InteractsWithExceptionHandling;

    /** @test */
    public function it_only_redirects_users_if_demo_mode_is_enabled()
    {
        $this->app['config']->set('demo-mode.enabled', false);

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
    public function it_will_not_create_the_demo_route_in_strict_mode()
    {
        $this->app['config']->set('demo-mode.strict_mode', true);

        $this->setUpRoutes($this->app);

        $this
            ->get('/demo')
            ->assertStatus(404);
    }

    /** @test */
    public function it_shows_the_default_404_page_when_demo_mode_is_disabled()
    {
        $this->app['config']->set('demo-mode.enabled', false);

        $this
            ->get('/non-existing-page')
            ->assertStatus(404);
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
