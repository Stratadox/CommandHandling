<?php

namespace Stratadox\CommandHandling;

use Throwable;

/**
 * Middleware that gets invoked
 */
interface ExceptionPathMiddleware
{
    /**
     * Invokes the middleware.
     *
     * Called upon when the command that is being handled throws an exception.
     *
     * @param object    $command   The command that is being handled.
     * @param Throwable $exception The exception that was encountered.
     */
    public function invoke(object $command, Throwable $exception): void;
}
