<?php

namespace Stegeman\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\V1\CloudEventInterface as V1CloudEventInterface;
use JMS\Serializer\SerializerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\NormalizerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\UnexpectedCloudEventVersion;

class Normalizer implements NormalizerInterface
{
    public function __construct(private readonly SerializerInterface $serializer) {}

    public function normalize(CloudEventInterface $cloudEvent): array
    {
        if ($cloudEvent instanceof V1CloudEventInterface === false) {
            throw new UnexpectedCloudEventVersion(
                sprintf(
                    'CloudEvent version 1.0 expected, %s given',
                    $cloudEvent->getSpecVersion()
                )
            );
        }

        return [
            'body' => [
                json_decode($this->serializer->serialize($cloudEvent->getData(), 'json'), true)
            ]
        ];
    }
}