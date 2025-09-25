<?php

namespace Messenger\Unit\Messenger\CloudEvents\Serializer;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Serializer\UuidGenerator;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Uid\Factory\RandomBasedUuidFactory;
use Symfony\Component\Uid\Uuid;

class UuidGeneratorTest extends TestCase
{
    #[Test]
    public function aUuidIsGenerated(): void
    {
        $UUIDGenerator = $this->getUUIDGenerator(
            $this->createUuidFactoryWithUuid4Call('69e1e5c8-78ff-4e2d-896b-5520754d5721')
        );

        $uuid = $UUIDGenerator->generate(new Envelope(new \stdClass()));

        self::assertSame('69e1e5c8-78ff-4e2d-896b-5520754d5721', $uuid);
    }

    private function getUUIDGenerator(RandomBasedUuidFactory $uuidFactory): UuidGenerator
    {
        return new UuidGenerator($uuidFactory);
    }

    private function createUuidFactoryWithUuid4Call(string $generatedUuid): RandomBasedUuidFactory
    {
        $uuidFactory = $this->createUuidFactoryInterface();

        $uuidFactory->expects($this->once())
            ->method('create')
            ->willReturn(Uuid::fromString($generatedUuid));

        return $uuidFactory;
    }

    private function createUuidFactoryInterface(): RandomBasedUuidFactory&MockObject
    {
        return $this->createMock(RandomBasedUuidFactory::class);

    }
}
