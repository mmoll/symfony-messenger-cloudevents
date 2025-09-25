<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

use CloudEvents\V1\CloudEventInterface;
use Stegeman\Messenger\CloudEvents\Converter\EnvelopeConverterInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\DenormalizerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\NormalizerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

readonly class CloudEventsSerializer implements SerializerInterface
{
    public function __construct(
        private EnvelopeConverterInterface $envelopeConverter,
        private NormalizerInterface $normalizer,
        private DenormalizerInterface $denormalizer
    ) {}

    public function decode(array $encodedCloudEvent): Envelope
    {
        $cloudEvent = $this->denormalizer->denormalize($encodedCloudEvent);

        return $this->envelopeConverter->fromCloudEvent($cloudEvent);
    }

    public function encode(Envelope $envelope): array
    {
        /** @var CloudEventInterface $cloudEvent */
        $cloudEvent = $this->envelopeConverter->toCloudEvent($envelope);

        return $this->normalizer->normalize($cloudEvent);
    }
}
