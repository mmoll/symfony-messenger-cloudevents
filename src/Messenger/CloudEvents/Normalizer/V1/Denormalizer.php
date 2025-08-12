<?php

namespace Stegeman\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\Serializers\Normalizers\V1\DenormalizerInterface as SdkDenormalizerInterface;
use JMS\Serializer\SerializerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\DenormalizerInterface;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;

readonly class Denormalizer implements DenormalizerInterface
{
    public function __construct(
        private MessageRegistryInterface $messageRegistry,
        private SerializerInterface $serializer,
        private SdkDenormalizerInterface $sdkDenormalizer
    ) {}

    public function denormalize(array $normalizedEvent): CloudEventInterface
    {
        $normalizedEventBody = $normalizedEvent['body'];

        $body = json_decode($normalizedEventBody, true);

        $message = $this->serializer->deserialize(
            json_encode($body['data']),
            $this->messageRegistry->getMessageClassNameForType($body['type']),
            'json'
        );

        $body['data'] = $message;

        return $this->sdkDenormalizer->denormalize($body);
    }
}