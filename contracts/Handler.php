<?php

namespace Stratadox\CommandHandling;

interface Handler
{
    /**
     * Handles an inputted command, either performing an action or throwing an
     * exception.
     *
     * @param object $command The command to handle.
     */
    public function handle(object $command): void;
}
