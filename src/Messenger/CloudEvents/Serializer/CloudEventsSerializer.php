<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

use CloudEvents\V1\CloudEvent;
use Stegeman\Messenger\CloudEvents\Factory\CloudEventToMessageConverterInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\DenormalizerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\NormalizerInterface;
use Symfony\Component\Messenger\Envelope;
use Stegeman\Messenger\CloudEvents\Factory\CloudEventFactoryInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use JMS\Serializer;

class CloudEventsSerializer implements SerializerInterface
{
    public function __construct(
        private readonly CloudEventToMessageConverterInterface $cloudEventToMessageConverter,
        private readonly CloudEventFactoryInterface $cloudEventFactory,
        private readonly NormalizerInterface $normalizer,
        private readonly DenormalizerInterface $denormalizer,
        private readonly Serializer\SerializerInterface $serializer,
        private readonly string $format
    ) {}

    public function decode(array $encodedEnvelope): Envelope
    {
        /** @var CloudEvent $cloudEvent */
        $cloudEvent = $this->denormalizer->denormalize($encodedEnvelope);

        $message = $this->cloudEventToMessageConverter->convert($cloudEvent);

        return new Envelope($message);
    }

    public function encode(Envelope $envelope): array
    {
        $event = $this->cloudEventFactory->buildForEnvelope($envelope, $this->format);

        $normalizedEvent = $this->normalizer->normalize($event);

        return [
            'body' => $this->serializer->serialize(
                $normalizedEvent,
                $this->format === 'application/json' ? 'json' : $this->format
            ),
            'headers' => ['Content-Type' => $this->format]
        ];
    }
}
