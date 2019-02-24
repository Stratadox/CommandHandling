<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test\Double\Handler;

use function assert;
use Stratadox\CommandHandling\Handler;
use Stratadox\CommandHandling\Test\Double\Command\SimpleCommand;
use Stratadox\CommandHandling\Test\Double\Output;

final class SimpleHandler implements Handler
{
    private $output;

    public function __construct(Output $output)
    {
        $this->output = $output;
    }

    /** @inheritdoc */
    public function handle(object $command): void
    {
        assert($command instanceof SimpleCommand);
        $this->output->simpleHandlerCalls();
    }
}

