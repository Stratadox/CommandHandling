<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\CommandHandling\CommandBus;
use Stratadox\CommandHandling\BeforeHandling;
use Stratadox\CommandHandling\Test\Double\Handler\AlwaysThrowingHandler;
use Stratadox\CommandHandling\Test\Double\CommandDenied;
use Stratadox\CommandHandling\Test\Double\Command\ParametrizedCommand;
use Stratadox\CommandHandling\Test\Double\Command\SimpleCommand;
use Stratadox\CommandHandling\Test\Double\Middleware\SpyingMiddleware;
use Stratadox\CommandHandling\Test\Double\Command\AlwaysFailingCommand;
use Stratadox\CommandHandling\Test\Double\Handler\VoidHandler;
use Throwable;

/**
 * @testdox Invoking the middleware before handling the command
 */
class InvokingMiddlewareBeforeHandling extends TestCase
{
    /**
     * @test
     * @testdox Invoking the middleware for all types (with "Simple command")
     */
    function invoking_the_middleware_for_all_types_with_simple_command()
    {
        $middleware = new SpyingMiddleware();
        $input = BeforeHandling::invoke($middleware, CommandBus::handling([
            SimpleCommand::class => new VoidHandler(),
            ParametrizedCommand::class => new VoidHandler(),
        ]));

        $input->handle(new SimpleCommand());

        $this->assertInstanceOf(
            SimpleCommand::class,
            $middleware->observedCommand()
        );
    }

    /**
     * @test
     * @testdox Invoking the middleware for all types (with "Parametrized command")
     */
    function invoking_the_middleware_for_all_types_with_parametrized_command()
    {
        $middleware = new SpyingMiddleware();
        $input = BeforeHandling::invoke($middleware, CommandBus::handling([
            SimpleCommand::class => new VoidHandler(),
            ParametrizedCommand::class => new VoidHandler(),
        ]));

        $input->handle(new ParametrizedCommand('foo', 'bar'));

        $this->assertInstanceOf(
            ParametrizedCommand::class,
            $middleware->observedCommand()
        );
    }

    /** @test */
    function invoking_the_middleware_for_rejected_commands()
    {
        $middleware = new SpyingMiddleware();
        $input = BeforeHandling::invoke($middleware, CommandBus::handling([
            SimpleCommand::class => new VoidHandler(),
            AlwaysFailingCommand::class => new AlwaysThrowingHandler(),
        ]));

        try {
            $input->handle(new AlwaysFailingCommand());

            $this->fail('Expecting an exception while handling the command.');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(CommandDenied::class, $exception);
        }
        $this->assertInstanceOf(
            AlwaysFailingCommand::class,
            $middleware->observedCommand()
        );
    }

    /** @test */
    function invoking_the_middleware_for_specific_commands()
    {
        $middleware = new SpyingMiddleware();
        $input = CommandBus::handling([
            SimpleCommand::class => BeforeHandling::invoke(
                $middleware,
                new VoidHandler()
            ),
            ParametrizedCommand::class => new VoidHandler(),
        ]);

        $input->handle(new SimpleCommand());

        $this->assertInstanceOf(SimpleCommand::class, $middleware->observedCommand());
    }

    /** @test */
    function invoking_the_middleware_only_for_specific_commands()
    {
        $middleware = new SpyingMiddleware();
        $input = CommandBus::handling([
            SimpleCommand::class => BeforeHandling::invoke(
                $middleware,
                new VoidHandler()
            ),
            ParametrizedCommand::class => new VoidHandler(),
        ]);

        $input->handle(new ParametrizedCommand('foo', 'bar'));

        $this->assertNull($middleware->observedCommand());
    }
}
