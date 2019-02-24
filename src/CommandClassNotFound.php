<?php declare(strict_types=1);

namespace Stratadox\CommandHandling;

use InvalidArgumentException as InvalidArgument;
use function sprintf;

/**
 * Exception to throw when trying to configure a command handler for a class of
 * commands that does not exist.
 */
final class CommandClassNotFound
    extends InvalidArgument
    implements InvalidBusConfiguration
{
    public static function tryingToConfigure($class): InvalidBusConfiguration
    {
        return new self(sprintf(
            'Cannot configure non-existing command class `%s`.',
            $class
        ));
    }
}
