<?php

namespace Stratadox\CommandHandling;

use Throwable;

interface Handler
{
    /**
     * Handles an inputted command, either performing an action or throwing an
     * exception.
     *
     * @param object $command The command to handle.
     * @throws Throwable      When the command is denied.
     */
    public function handle(object $command): void;
}
