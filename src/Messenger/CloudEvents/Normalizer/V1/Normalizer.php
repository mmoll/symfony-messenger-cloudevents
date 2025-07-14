<?php

namespace Stegeman\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\Serializers\Normalizers\V1\NormalizerInterface as SdkNormalizerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\NormalizerInterface;

readonly class Normalizer implements NormalizerInterface
{
    public function __construct(private SdkNormalizerInterface $normalizer) {}

    public function normalize(CloudEventInterface $cloudEvent): array
    {
        return $this->normalizer->normalize($cloudEvent, true);
    }
}