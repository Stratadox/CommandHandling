<?php declare(strict_types=1);

namespace Stratadox\CommandHandling\Test\Double\Command;

final class ParametrizedCommand
{
    private $param1;
    private $param2;

    public function __construct(string $param1, string $param2)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
    }

    public function param1(): string
    {
        return $this->param1;
    }

    public function param2(): string
    {
        return $this->param2;
    }
}
