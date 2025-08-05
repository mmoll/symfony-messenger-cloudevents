<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

interface MessageRegistryInterface
{
    public function getTypeForMessageClass(string $messageClass): string;

    public function getMessageClassNameForType(string $type): string;
}