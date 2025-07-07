<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

use Stegeman\Messenger\CloudEvents\Normalizer\NormalizerInterface;
use Symfony\Component\Messenger\Envelope;
use Stegeman\Messenger\CloudEvents\Factory\CloudEventFactoryInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use JMS\Serializer;

class CloudEventsSerializer implements SerializerInterface
{
    public function __construct(
        private readonly CloudEventFactoryInterface $cloudEventFactory,
        private readonly NormalizerInterface $normalizer,
        private readonly Serializer\SerializerInterface $serializer
    ) {}

    public function decode(array $encodedEnvelope): Envelope
    {
        // TODO: Implement decode() method.
    }


    public function encode(Envelope $envelope): array
    {
        $event = $this->cloudEventFactory->buildForEnvelope($envelope);

        $normalizedEvent = $this->normalizer->normalize($event);

        return [
            'body' => $this->serializer->serialize($normalizedEvent, 'json'),
            'headers' => []
        ];
    }
}
