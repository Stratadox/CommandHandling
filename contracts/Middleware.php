<?php

namespace Stratadox\CommandHandling;

interface Middleware
{
    /**
     * Invokes the middleware.
     *
     * Called upon when the command is being handled.
     *
     * @param object $command The command that is being handled.
     */
    public function invoke(object $command): void;
}
