<?php declare(strict_types=1);

namespace Stratadox\CommandHandling;

use Throwable;

/**
 * Decorator that invokes the middleware when handling a command failed.
 *
 * Used to configure middleware that runs when an exception is encountered.
 * Such middleware could, for example:
 * - log the exception
 * - roll back a unit of work or database transaction
 * - etc
 */
final class OnExceptionCases implements Handler
{
    private $middleware;
    private $handler;

    private function __construct(
        ExceptionPathMiddleware $middleware,
        Handler $handler
    ) {
        $this->middleware = $middleware;
        $this->handler = $handler;
    }

    public static function invoke(
        ExceptionPathMiddleware $middleware,
        Handler $handler
    ): Handler {
        return new self($middleware, $handler);
    }

    /** @inheritdoc */
    public function handle(object $command): void
    {
        try {
            $this->handler->handle($command);
        } catch (Throwable $exception) {
            $this->middleware->invoke($command, $exception);
        }
    }
}
