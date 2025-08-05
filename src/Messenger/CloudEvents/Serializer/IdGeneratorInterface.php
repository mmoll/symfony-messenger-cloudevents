<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

use Symfony\Component\Messenger\Envelope;

interface IdGeneratorInterface
{
    public function generate(Envelope $envelope): string;
}