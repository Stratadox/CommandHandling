<?php declare(strict_types=1);

namespace Stratadox\CommandHandling;

/**
 * Decorator that invokes the middleware after successfully handling a command.
 *
 * Used to configure middleware that runs after command handling.
 * Such middleware could, for example:
 * - log the successful processing of the command
 * - save the changes to the data store
 * - etc
 */
final class AfterHandling implements Handler
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
        $this->handler->handle($command);
        $this->middleware->invoke($command);
    }
}
