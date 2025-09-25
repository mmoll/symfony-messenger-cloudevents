<?php

namespace Stegeman\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\Serializers\Normalizers\V1\NormalizerInterface as SdkNormalizerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class Normalizer implements NormalizerInterface
{
    public function __construct(
        private SdkNormalizerInterface $normalizer,
        private SerializerInterface $serializer
    ) {}

    public function normalize(CloudEventInterface $cloudEvent): array
    {
        $normalizedEvent = $this->normalizer->normalize($cloudEvent, true);

        $serializedNormalizedEvent = $this->serializer->serialize($normalizedEvent, 'json');

        return  [
            'body' => $serializedNormalizedEvent,
            'headers' => ['Content-Type' => $cloudEvent->getDataContentType()]
        ];
    }
}
