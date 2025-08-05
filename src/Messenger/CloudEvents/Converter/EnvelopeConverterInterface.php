<?php

namespace Stegeman\Messenger\CloudEvents\Converter;

use CloudEvents\CloudEventInterface;
use Symfony\Component\Messenger\Envelope;

interface EnvelopeConverterInterface
{
    public function fromCloudEvent(CloudEventInterface $cloudEvent): Envelope;

    public function toCloudEvent(Envelope $envelope): CloudEventInterface;
}