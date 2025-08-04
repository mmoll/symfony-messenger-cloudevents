<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

class MessageRegistry implements MessageRegistryInterface
{
    /** @var array<string, string> */
    private array $messageMapping = [];

    public function getNameForMessage(object $message): string
    {
        foreach ($this->messageMapping as $messageName => $messageObjectName) {
            if ($messageObjectName === get_class($message)) {
                return $messageName;
            }
        }

        throw new NoNameFoundForMessageException(sprintf('No name found for message of type %s', get_class($message)));
    }

    public function addMessage(string $messageName, string $messageObjectName): void
    {
        $this->messageMapping[$messageName] = $messageObjectName;
    }

    public function getMessageClassNameForType(string $name): string
    {

    }
}