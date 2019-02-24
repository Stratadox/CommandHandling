<?php declare(strict_types=1);

namespace Stratadox\CommandHandling;

/**
 * Decorator that invokes the middleware before starting to handle a command.
 *
 * Used to configure middleware that runs before command handling.
 * Such middleware could, for example:
 * - fail early if the request is not authenticated
 * - log the command handling attempt
 * - start a unit of work or database transaction
 * - etc
 */
final class BeforeHandling implements Handler
{
    private $middleware;
    private $handler;

    private function __construct(Middleware $middleware, Handler $handler)
    {
        $this->middleware = $middleware;
        $this->handler = $handler;
    }

    public static function invoke(
        Middleware $middleware,
        Handler $handler
    ): Handler {
        return new self($middleware, $handler);
    }

    /** @inheritdoc */
    public function handle(object $command): void
    {
        $this->middleware->invoke($command);
        $this->handler->handle($command);
    }
}
