<?php

namespace Stegeman\Messenger\CloudEvents\Serializer;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Uid\Factory\RandomBasedUuidFactory;
use Symfony\Component\Uid\UuidV4;

readonly class UuidGenerator implements IdGeneratorInterface

{
    private RandomBasedUuidFactory $uuidFactory;

    public function __construct(?RandomBasedUuidFactory $uuidFactory)  {
        $this->uuidFactory = $uuidFactory ?? new RandomBasedUuidFactory(UuidV4::class);
    }


    public function generate(Envelope $envelope): string
    {
        return $this->uuidFactory->create()->toString();
    }
}
