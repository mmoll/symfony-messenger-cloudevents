<?php

namespace Stegeman\Messenger\CloudEvents\Factory\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\V1\CloudEvent;
use Stegeman\Messenger\CloudEvents\Serializer\IdGeneratorInterface;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Symfony\Component\Messenger\Envelope;
use Stegeman\Messenger\CloudEvents\Factory\CloudEventFactoryInterface;

class CloudEventFactory implements CloudEventFactoryInterface
{
    public function __construct(
        private readonly IdGeneratorInterface     $idGenerator,
        private readonly MessageRegistryInterface $messageRegistry
    ) {}

    public function buildForEnvelope(Envelope $envelope, string $dataContentType): CloudEventInterface
    {
        $eventName = $this->messageRegistry->getNameForMessage($envelope->getMessage());
        $eventId = $this->idGenerator->generate($envelope->getMessage());


        $event = new CloudEvent(
            id: $eventId,
            source: $eventName,
            type: $eventName,
            data: $envelope->getMessage(),
            dataContentType: $dataContentType,
            time: (new \DateTimeImmutable()),
        );

        return $event;
    }
}