<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\CommandHandling\CommandBus;
use Stratadox\CommandHandling\OnExceptionCases;
use Stratadox\CommandHandling\AfterHandling;
use Stratadox\CommandHandling\Test\Double\Handler\AlwaysThrowingHandler;
use Stratadox\CommandHandling\Test\Double\CommandDenied;
use Stratadox\CommandHandling\Test\Double\Command\ParametrizedCommand;
use Stratadox\CommandHandling\Test\Double\Command\SimpleCommand;
use Stratadox\CommandHandling\Test\Double\Middleware\FailureSpyingMiddleware;
use Stratadox\CommandHandling\Test\Double\Middleware\SpyingMiddleware;
use Stratadox\CommandHandling\Test\Double\Command\AlwaysFailingCommand;
use Stratadox\CommandHandling\Test\Double\Handler\VoidHandler;
use Throwable;

/**
 * @testdox Invoking the middleware after failing to handle a command
 */
class InvokingMiddlewareInExceptionCases extends TestCase
{
    /** @test */
    function invoking_the_middleware_for_rejected_commands()
    {
        $middleware = new FailureSpyingMiddleware();
        $input = OnExceptionCases::invoke($middleware, CommandBus::handling([
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
        $this->assertInstanceOf(
            CommandDenied::class,
            $middleware->observedException()
        );
    }

    /** @test */
    function not_invoking_the_middleware_for_successful_commands()
    {
        $middleware = new FailureSpyingMiddleware();
        $input = OnExceptionCases::invoke($middleware, CommandBus::handling([
            SimpleCommand::class => new VoidHandler(),
            AlwaysFailingCommand::class => new AlwaysThrowingHandler(),
        ]));

        $input->handle(new SimpleCommand());

        $this->assertNull($middleware->observedCommand());
        $this->assertNull($middleware->observedException());
    }

    /** @test */
    function invoking_the_middleware_for_specific_commands_only()
    {
        $middleware = new FailureSpyingMiddleware();
        $input = CommandBus::handling([
            SimpleCommand::class => OnExceptionCases::invoke(
                $middleware,
                new VoidHandler()
            ),
            AlwaysFailingCommand::class => new AlwaysThrowingHandler(),
        ]);

        try {
            $input->handle(new AlwaysFailingCommand());

            $this->fail('Expecting an exception while handling the command.');
        } catch (Throwable $exception) {
            $this->assertInstanceOf(CommandDenied::class, $exception);
        }
        $this->assertNull($middleware->observedCommand());
        $this->assertNull($middleware->observedException());
    }
}
