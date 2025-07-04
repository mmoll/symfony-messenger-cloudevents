<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

interface IdGeneratorInterface
{
    public function generate(object $message): string;
}