<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\CommandHandling\CommandBus;
use Stratadox\CommandHandling\CommandNotRecognised;
use Stratadox\CommandHandling\Handler;
use Stratadox\CommandHandling\Test\Double\Handler\AlwaysThrowingHandler;
use Stratadox\CommandHandling\Test\Double\CommandDenied;
use Stratadox\CommandHandling\Test\Double\Command\ParametrizedCommand;
use Stratadox\CommandHandling\Test\Double\Command\NotConfiguredCommand;
use Stratadox\CommandHandling\Test\Double\Output;
use Stratadox\CommandHandling\Test\Double\Handler\ParametrizedHandler;
use Stratadox\CommandHandling\Test\Double\Command\SimpleCommand;
use Stratadox\CommandHandling\Test\Double\Handler\SimpleHandler;
use Stratadox\CommandHandling\Test\Double\Command\AlwaysFailingCommand;

/**
 * @testdox Handling the commands
 */
class HandlingTheCommands extends TestCase
{
    /** @var Handler */
    private $input;
    /** @var Output */
    private $output;

    protected function setUp(): void
    {
        $this->output = new Output();
        $this->input = CommandBus::handling([
            SimpleCommand::class => new SimpleHandler($this->output),
            ParametrizedCommand::class => new ParametrizedHandler($this->output),
            AlwaysFailingCommand::class => new AlwaysThrowingHandler(),
        ]);
    }

    /** @test */
    function executing_a_simple_command()
    {
        $this->input->handle(new SimpleCommand());

        $this->assertTrue($this->output->wasCalledBySimpleHandler());
    }

    /** @test */
    function not_executing_a_simple_command()
    {
        $this->assertFalse($this->output->wasCalledBySimpleHandler());
    }

    /**
     * @test
     * @testdox Executing a command with parameters "foo" and "bar"
     */
    function executing_a_command_with_parameters_foo_and_bar()
    {
        $this->input->handle(new ParametrizedCommand('foo', 'bar'));

        $this->assertTrue($this->output->wasCalledByParametrizedHandler());
        $this->assertSame(
            ['foo', 'bar'],
            $this->output->parametersGivenByParametrizedHandler()
        );
    }

    /**
     * @test
     * @testdox Executing a command with parameters "bar" and "baz"
     */
    function executing_a_command_with_parameters_bar_and_baz()
    {
        $this->input->handle(new ParametrizedCommand('bar', 'baz'));

        $this->assertTrue($this->output->wasCalledByParametrizedHandler());
        $this->assertSame(
            ['bar', 'baz'],
            $this->output->parametersGivenByParametrizedHandler()
        );
    }

    /** @test */
    function not_executing_a_command_with_parameters()
    {
        $this->assertFalse($this->output->wasCalledByParametrizedHandler());
    }

    /** @test */
    function trying_to_execute_a_command_that_gets_denied()
    {
        $this->expectException(CommandDenied::class);

        $this->input->handle(new AlwaysFailingCommand());
    }

    /** @test */
    function trying_to_execute_a_command_that_is_not_configured()
    {
        $this->expectException(CommandNotRecognised::class);

        $this->input->handle(new NotConfiguredCommand());
    }
}
