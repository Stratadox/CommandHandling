<?php declare(strict_types=1);

namespace Stratadox\CommandHandling;

use function get_class;
use function gettype;
use InvalidArgumentException as InvalidArgument;
use function is_object;
use function sprintf;

/**
 * Exception to throw when trying to configure a command handler that does not
 * implement the expected interface.
 */
final class InvalidCommandHandler
    extends InvalidArgument
    implements InvalidBusConfiguration
{
    public static function triedToAssign($handler): InvalidBusConfiguration
    {
        return new self(
            is_object($handler)
            ? sprintf(
                'The requested command handler `%s` does not implement `%s`.',
                get_class($handler),
                Handler::class
            )
            : sprintf(
                'Cannot assign a primitive %s as command handler.',
                gettype($handler)
            )
        );
    }
}
