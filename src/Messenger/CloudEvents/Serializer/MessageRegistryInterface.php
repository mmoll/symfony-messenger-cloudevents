<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

interface MessageRegistryInterface
{
    /** @TODO Rename to getNameForMessageClass */
    public function getNameForMessage(object $message): string;

    public function getMessageClassNameForType(string $type): string;
}