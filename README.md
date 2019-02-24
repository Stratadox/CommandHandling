# Command Handling
A simple command handling mechanism.

[![Build Status](https://travis-ci.org/Stratadox/CommandHandling.svg?branch=master)](https://travis-ci.org/Stratadox/CommandHandling)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/CommandHandling/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/CommandHandling?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/CommandHandling/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/CommandHandling/?branch=master)

## Installation
Install using `composer require stratadox/command-handling`

## Example
```php
<?php
use Stratadox\CommandHandling\CommandBus;

$bus = CommandBus::handling([
    SomeCommand::class => new SomeHandler(),
    OtherCommand::class => new OtherHandler(),
]);
$bus->handle(new SomeCommand('foo'));
```

## Glossary
### Command
Commands are simple messages (*DTO*) that represent a request for an operation.
These commands are not the same kind as described in the [GOF design patterns](https://en.wikipedia.org/wiki/Command_pattern), 
but rather command messages from the CQRS realm.

### Handler
A command handler receives the commands, either accepting them and initiating 
the operation, or denying them and throwing an exception.

### Bus
The command bus routes the input command to the appropriate handler.

### Middleware
Middleware can be configured to perform operations before or after handling 
commands, or on exception cases.

Such middleware can be used to introduce automated logging of all requests, to 
automatically reject unauthorised access or to handle persistence concerns.
Using the middleware functionality in this way grants the benefits of what 
people call aspect-oriented programming, without most of the downsides that come 
with.
