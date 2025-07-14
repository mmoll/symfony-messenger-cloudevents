<?php

namespace Stegeman\Messenger\CloudEvents\Factory;

use CloudEvents\CloudEventInterface;
use Symfony\Component\Messenger\Envelope;

interface CloudEventFactoryInterface
{
    public function buildForEnvelope(Envelope $envelope, string $dataContentType): CloudEventInterface;
}