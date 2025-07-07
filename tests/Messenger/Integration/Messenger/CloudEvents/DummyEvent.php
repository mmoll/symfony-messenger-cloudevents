<?php

namespace Stegeman\Tests\Messenger\Integration\Messenger\CloudEvents;

class DummyEvent
{
    public function __construct(
        private readonly string $id,
        private readonly string $name
    ) {}
}