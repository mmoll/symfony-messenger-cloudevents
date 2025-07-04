<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

interface MessageRegistryInterface
{
    public function getNameForMessage(object $message): string;
}