<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

use Stegeman\Messenger\CloudEvents\Normalizer\NormalizerInterface;
use Symfony\Component\Messenger\Envelope;
use Stegeman\Messenger\CloudEvents\Factory\CloudEventFactoryInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class CloudEventsSerializer implements SerializerInterface
{
    public function __construct(
        private readonly CloudEventFactoryInterface $cloudEventFactory,
        private readonly NormalizerInterface $normalizer,
    ) {}

    public function decode(array $encodedEnvelope): Envelope
    {
        // TODO: Implement decode() method.
    }


    public function encode(Envelope $envelope): array
    {
        $event = $this->cloudEventFactory->buildForEnvelope($envelope);

        return $this->normalizer->normalize($event);
    }
}
