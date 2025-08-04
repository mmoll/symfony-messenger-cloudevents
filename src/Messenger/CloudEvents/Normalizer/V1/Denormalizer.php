<?php

namespace Stegeman\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\Serializers\Normalizers\V1\DenormalizerInterface as SdkDenormalizerInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\DenormalizerInterface;

class Denormalizer implements DenormalizerInterface
{
    public function __construct(private readonly SdkDenormalizerInterface $sdkDenormalizer) {}

    public function denormalize(array $normalizedEvent): CloudEventInterface
    {
        return $this->sdkDenormalizer->denormalize($normalizedEvent);
    }
}