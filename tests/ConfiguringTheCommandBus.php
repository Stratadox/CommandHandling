<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test;

use PHPUnit\Framework\TestCase;
use stdClass;
use Stratadox\CommandHandling\CommandBus;
use Stratadox\CommandHandling\InvalidBusConfiguration;
use Stratadox\CommandHandling\Test\Double\Command\SimpleCommand;
use Stratadox\CommandHandling\Test\Double\Handler\VoidHandler;

/**
 * @testdox Configuring the command bus
 */
class ConfiguringTheCommandBus extends TestCase
{
    /** @test */
    function cannot_use_non_existing_class_names()
    {
        $this->expectException(InvalidBusConfiguration::class);
        $this->expectExceptionMessageRegExp(
            '/non-existing.*class `non-existing class name`/'
        );

        CommandBus::handling(['non-existing class name' => new VoidHandler()]);
    }

    /** @test */
    function cannot_use_numeric_class_names()
    {
        $this->expectException(InvalidBusConfiguration::class);
        $this->expectExceptionMessageRegExp(
            '/non-existing.*class `10`/'
        );

        CommandBus::handling([10 => new VoidHandler()]);
    }

    /** @test */
    function cannot_use_strings_as_handlers()
    {
        $this->expectException(InvalidBusConfiguration::class);
        $this->expectExceptionMessageRegExp(
            '/primitive.*string/'
        );

        CommandBus::handling([SimpleCommand::class => VoidHandler::class]);
    }

    /** @test */
    function cannot_use_integers_as_handlers()
    {
        $this->expectException(InvalidBusConfiguration::class);
        $this->expectExceptionMessageRegExp(
            '/primitive.*integer/'
        );

        CommandBus::handling([VoidHandler::class => 1]);
    }

    /** @test */
    function cannot_use_arrays_as_handlers()
    {
        $this->expectException(InvalidBusConfiguration::class);
        $this->expectExceptionMessageRegExp(
            '/primitive.*array/'
        );

        CommandBus::handling([SimpleCommand::class => [new VoidHandler()]]);
    }

    /** @test */
    function cannot_use_non_handler_objects_as_handlers()
    {
        $this->expectException(InvalidBusConfiguration::class);
        $this->expectExceptionMessageRegExp(
            '/(does not|must) implement/'
        );

        CommandBus::handling([SimpleCommand::class => new stdClass()]);
    }
}
