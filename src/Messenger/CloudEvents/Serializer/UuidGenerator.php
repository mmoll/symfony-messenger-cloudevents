<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

use Ramsey\Uuid\UuidFactoryInterface;

readonly class UuidGenerator implements IdGeneratorInterface
{
    public function __construct(private UuidFactoryInterface $uuidFactory) {}


    public function generate(object $message): string
    {
        return $this->uuidFactory->uuid4()->toString();
    }
}