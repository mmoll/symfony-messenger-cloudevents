<?php

namespace Tests\Stegeman\Messenger\CloudEvents;

class DummyEvent
{
    public function __construct(
        private readonly string $id,
        private readonly string $name
    ) {}
}