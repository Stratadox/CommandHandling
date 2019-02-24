<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test\Double\Middleware;

use Stratadox\CommandHandling\Middleware;

final class SpyingMiddleware implements Middleware
{
    private $observedCommand;

    /** @inheritdoc */
    public function invoke(object $command): void
    {
        $this->observedCommand = $command;
    }

    public function observedCommand(): ?object
    {
        return $this->observedCommand;
    }
}
