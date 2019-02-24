<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test\Double\Handler;

use function assert;
use Stratadox\CommandHandling\Handler;
use Stratadox\CommandHandling\Test\Double\Command\AlwaysFailingCommand;
use Stratadox\CommandHandling\Test\Double\CommandDenied;

final class AlwaysThrowingHandler implements Handler
{
    /** @inheritdoc */
    public function handle(object $command): void
    {
        assert($command instanceof AlwaysFailingCommand);
        throw new CommandDenied();
    }
}

