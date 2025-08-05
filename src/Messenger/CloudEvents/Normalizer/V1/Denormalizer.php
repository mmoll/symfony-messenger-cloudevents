<?php

namespace Stegeman\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\Serializers\Normalizers\V1\DenormalizerInterface as SdkDenormalizerInterface;
use JMS\Serializer\SerializerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\DenormalizerInterface;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\DummyEvent;

readonly class Denormalizer implements DenormalizerInterface
{
    public function __construct(
        private MessageRegistryInterface $messageRegistry,
        private SerializerInterface $serializer,
        private SdkDenormalizerInterface $sdkDenormalizer
    ) {}

    public function denormalize(array $normalizedEvent): CloudEventInterface
    {
        $message = $this->serializer->deserialize($normalizedEvent['body'], DummyEvent::class, 'json');

        $normalizedEvent['body'] = $message;

        return $this->sdkDenormalizer->denormalize($normalizedEvent);
    }
}