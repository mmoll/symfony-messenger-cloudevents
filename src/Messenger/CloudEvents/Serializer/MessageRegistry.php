<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

class MessageRegistry implements MessageRegistryInterface
{
    /** @var array<string, string> */
    private array $messageMapping = [];

    public function getTypeForMessageClass(string $messageClass): string
    {
        foreach ($this->messageMapping as $messageName => $messageObjectName) {
            if ($messageObjectName === $messageClass) {
                return $messageName;
            }
        }

        throw new NoNameFoundForMessageException(sprintf('No name found for message of type %s', $messageClass));
    }

    public function addMessage(string $messageName, string $messageObjectName): void
    {
        $this->messageMapping[$messageName] = $messageObjectName;
    }

    public function getMessageClassNameForType(string $name): string
    {

    }
}