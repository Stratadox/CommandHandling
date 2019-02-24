<?php declare(strict_types=1);

namespace Stratadox\CommandHandling;

use function array_key_exists;
use function class_exists;
use function get_class;
use function is_string;

/**
 * The command bus is a handler that can take different types of commands,
 * delegating them to their designated command handlers.
 */
final class CommandBus implements Handler
{
    /** @var Handler[] */
    private $registry;

    /** @throws InvalidBusConfiguration */
    private function __construct(array $registry)
    {
        foreach ($registry as $commandClass => $handler) {
            $this->mustBeAnExisting($commandClass);
            $this->mustBeACompatible($handler);
        }
        $this->registry = $registry;
    }

    /** @throws InvalidBusConfiguration */
    public static function handling(array $commands): Handler
    {
        return new self($commands);
    }

    /** @inheritdoc */
    public function handle(object $command): void
    {
        $this->mustBeAKnown($command);
        $this->registry[get_class($command)]->handle($command);
    }

    /** @throws InvalidBusConfiguration */
    private function mustBeAnExisting($commandClass): void
    {
        if (!is_string($commandClass) || !class_exists($commandClass)) {
            throw CommandClassNotFound::tryingToConfigure($commandClass);
        }
    }

    /** @throws InvalidBusConfiguration */
    private function mustBeACompatible($commandHandler): void
    {
        if (!$commandHandler instanceof Handler) {
            throw InvalidCommandHandler::triedToAssign($commandHandler);
        }
    }

    /** @throws CommandNotRecognised */
    private function mustBeAKnown($command): void
    {
        if (!array_key_exists(get_class($command), $this->registry)) {
            throw CommandNotRecognised::triedToHandle($command);
        }
    }
}
