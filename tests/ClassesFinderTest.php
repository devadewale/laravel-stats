<?php

namespace Wnx\LaravelStats\Tests;

use Wnx\LaravelStats\ClassesFinder;

class ClassesFinderTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        config()->set('stats', [
            'paths' => [
                __DIR__.'/../tests/Stubs',
            ],
            'exclude' => [
                __DIR__.'/../tests/Stubs/ExcludedFile.php',
            ],
            'ignored_namespaces' => [],
        ]);

        $this->classes = app(ClassesFinder::class)->findAndLoadClasses();
    }

    /** @test */
    public function it_finds_controllers()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Controllers\ProjectsController::class));
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Controllers\Controller::class));
    }

    /** @test */
    public function it_finds_commands()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Commands\DemoCommand::class));
    }

    /** @test */
    public function it_finds_events()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Events\DemoEvent::class));
    }

    /** @test */
    public function it_finds_jobs()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Jobs\DemoJob::class));
    }

    /** @test */
    public function it_finds_mails()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Mails\DemoMail::class));
    }

    /** @test */
    public function it_finds_middlewares()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Middlewares\DemoMiddleware::class));
    }

    /** @test */
    public function it_finds_route_middlewares()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Middlewares\RouteMiddleware::class));
    }

    /** @test */
    public function it_finds_migrations()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Migrations\CreateUsersTable::class));
    }

    /** @test */
    public function it_finds_models()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Models\Project::class));
    }

    /** @test */
    public function it_finds_notifications()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Notifications\ServerDownNotification::class));
    }

    /** @test */
    public function it_finds_policies()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Policies\DemoPolicy::class));
    }

    /** @test */
    public function it_finds_request()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Requests\UserRequest::class));
    }

    /** @test */
    public function it_finds_resources()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Resources\DemoResource::class));
    }

    /** @test */
    public function it_finds_rules()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Rules\DemoRule::class));
    }

    /** @test */
    public function it_finds_seeders()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\Seeders\DemoSeeder::class));
    }

    /** @test */
    public function it_finds_service_providers()
    {
        $this->assertTrue($this->classes->contains(\Wnx\LaravelStats\Tests\Stubs\ServiceProviders\DemoProvider::class));
    }

    /** @test */
    public function it_includes_native_php_classes()
    {
        $this->assertTrue($this->classes->contains('stdClass'));
        $this->assertTrue($this->classes->contains('Exception'));
    }

    /** @test */
    public function it_ignores_exluded_file()
    {
        $this->assertFalse($this->classes->contains('ExcludedFile'));
    }

    /** @test */
    public function it_includes_vendored_classes()
    {
        $this->assertTrue($this->classes->contains(\Symfony\Component\Finder\Finder::class));
    }
}
