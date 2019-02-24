<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test\Double\Handler;

use Stratadox\CommandHandling\Handler;

final class VoidHandler implements Handler
{
    /** @inheritdoc */
    public function handle(object $command): void
    {
    }
}

