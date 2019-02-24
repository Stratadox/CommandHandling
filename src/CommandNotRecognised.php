<?php declare(strict_types=1);

namespace Stratadox\CommandHandling;

use function get_class;
use InvalidArgumentException as InvalidArgument;
use function sprintf;

/**
 * Exception to throw when trying to handle a command that has no corresponding
 * command handler.
 */
final class CommandNotRecognised extends InvalidArgument
{
    public static function triedToHandle(object $command): self
    {
        return new self(sprintf(
            'No command handler is configured to handle the command `%s`.',
            get_class($command)
        ));
    }
}
