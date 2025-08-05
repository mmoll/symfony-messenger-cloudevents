<?php

namespace Stegeman\Messenger\CloudEvents\Converter\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\V1\CloudEvent;
use Stegeman\Messenger\CloudEvents\Converter\EnvelopeConverterInterface;
use Stegeman\Messenger\CloudEvents\Serializer\IdGeneratorInterface;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Symfony\Component\Messenger\Envelope;

readonly class EnvelopeConverter implements EnvelopeConverterInterface
{
    public function __construct(
        private MessageRegistryInterface $messageRegistry,
        private IdGeneratorInterface $idGenerator
    ) {}

    public function fromCloudEvent(CloudEventInterface $cloudEvent): Envelope
    {
        return new Envelope($cloudEvent->getData());
    }

    public function toCloudEvent(Envelope $envelope): CloudEventInterface
    {
        $cloudEventId = $this->idGenerator->generate($envelope);
        $type = $this->messageRegistry->getTypeForMessageClass($envelope->getMessage()::class);

        return new CloudEvent(
            $cloudEventId,
            $type,
            $type,
            $envelope->getMessage(),
            "application/json"
        );
    }
}
