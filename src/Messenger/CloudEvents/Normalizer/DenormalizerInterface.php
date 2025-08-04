<?php

namespace Stegeman\Messenger\CloudEvents\Normalizer;

use CloudEvents\CloudEventInterface;

interface DenormalizerInterface
{
    public function denormalize(array $normalizedEvent): CloudEventInterface;
}
