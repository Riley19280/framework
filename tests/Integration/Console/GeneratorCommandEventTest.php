<?php

namespace Illuminate\Tests\Integration\Console;

use Illuminate\Console\Events\StubCreated;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase;

class GeneratorCommandEventTest extends TestCase
{
    public function testItDispatchesStubCreatedEvent()
    {
        Event::fake();

        $this->artisan('make:command', ['name' => 'dispatchStubEventTest'])
            ->assertExitCode(0);

        Event::assertDispatched(StubCreated::class, function (StubCreated $stubCreatedEvent) {
            $this->assertStringEndsWith('/stubs/console.stub', str_replace(DIRECTORY_SEPARATOR, '/', $stubCreatedEvent->stubPath));
            $this->assertStringEndsWith('/app/Console/Commands/dispatchStubEventTest.php', str_replace(DIRECTORY_SEPARATOR, '/', $stubCreatedEvent->outputPath));

            return true;
        });
    }

    protected function tearDown(): void
    {
        // Remove the file generated by the make:command command
        unlink(app_path('Console/Commands/dispatchStubEventTest.php'));

        parent::tearDown();
    }
}
