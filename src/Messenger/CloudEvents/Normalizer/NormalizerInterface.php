<?php

namespace Stegeman\Messenger\CloudEvents\Normalizer;

use CloudEvents\CloudEventInterface;

interface NormalizerInterface
{
    /**
     * @throws UnexpectedCloudEventVersion
     */
    public function normalize(CloudEventInterface $cloudEvent): array;
}