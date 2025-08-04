<?php

namespace Stegeman\Messenger\CloudEvents\Factory;

use CloudEvents\CloudEventInterface;

interface CloudEventToMessageConverterInterface
{
    public function convert(CloudEventInterface $cloudEvent): object;
}