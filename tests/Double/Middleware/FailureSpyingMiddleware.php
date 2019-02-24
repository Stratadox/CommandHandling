<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test\Double\Middleware;

use Stratadox\CommandHandling\ExceptionPathMiddleware;
use Throwable;

final class FailureSpyingMiddleware implements ExceptionPathMiddleware
{
    private $observedCommand;
    private $observedException;

    /** @inheritdoc */
    public function invoke(object $command, Throwable $exception): void
    {
        $this->observedCommand = $command;
        $this->observedException = $exception;
    }

    public function observedCommand(): ?object
    {
        return $this->observedCommand;
    }

    public function observedException(): ?Throwable
    {
        return $this->observedException;
    }
}
