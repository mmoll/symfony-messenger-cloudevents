<?php

namespace Stegeman\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\Serializers\Normalizers\V1\NormalizerInterface as SdkNormalizerInterface;
use CloudEvents\V1\CloudEventInterface as V1CloudEventInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\NormalizerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\UnexpectedCloudEventVersion;

readonly class Normalizer implements NormalizerInterface
{
    public function __construct(private SdkNormalizerInterface $normalizer) {}

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

        return $this->normalizer->normalize($cloudEvent, true);
    }
}