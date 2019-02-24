<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test\Double;

final class Output
{
    private $calledBySimpleHandler = false;
    private $calledByParametrizedHandler = false;
    private $parametersGivenByParametrizedHandler = [];

    public function simpleHandlerCalls(): void
    {
        $this->calledBySimpleHandler = true;
    }

    public function parametrizedHandlerCalls(string ...$arguments): void
    {
        $this->calledByParametrizedHandler = true;
        $this->parametersGivenByParametrizedHandler = $arguments;
    }

    public function wasCalledBySimpleHandler(): bool
    {
        return $this->calledBySimpleHandler;
    }

    public function wasCalledByParametrizedHandler(): bool
    {
        return $this->calledByParametrizedHandler;
    }

    public function parametersGivenByParametrizedHandler(): array
    {
        return $this->parametersGivenByParametrizedHandler;
    }
}
