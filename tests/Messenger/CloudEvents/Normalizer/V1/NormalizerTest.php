<?php

namespace App\Tests\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\V1\CloudEventInterface;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Normalizer\UnexpectedCloudEventVersion;
use Stegeman\Messenger\CloudEvents\Normalizer\V1\Normalizer;

class NormalizerTest extends TestCase
{
    #[Test]
    public function cloudEventIsNormalizedToArray(): void
    {
        $normalizer = $this->getNormalizer($this->createSerializerWithSerializeCall());

        $result = $normalizer->normalize($this->createCloudEventWithData());

        self::assertIsArray($result);
        self::assertSame(
            [
                'body' => ["id" => 12345]
            ],
            $result
        );
    }

    #[Test]
    public function anExceptionIsThrownIfNonV1CloudEventIsGiven(): void
    {
        $this->expectException(UnexpectedCloudEventVersion::class);
        $this->expectExceptionMessage('CloudEvent version 1.0 expected, 2.0 given');

        $normalizer = $this->getNormalizer($this->createSerializerInterface());

        $normalizer->normalize($this->createCloudEventWithWrongSpecVersion());
    }

    private function getNormalizer(SerializerInterface $serializer): Normalizer
    {
        return new Normalizer($serializer);
    }

    private function createCloudEventWithData(): CloudEventInterface
    {
        $cloudEvent = $this->createV1CloudEventInterface();

        $cloudEvent->expects($this->any())
            ->method('getId')
            ->willReturn('12345');

        return $cloudEvent;
    }

    private function createCloudEventWithWrongSpecVersion(): \CloudEvents\CloudEventInterface
    {
        $cloudEvent = $this->createMock(\CloudEvents\CloudEventInterface::class);

        $cloudEvent->expects($this->any())
            ->method('getSpecVersion')
            ->willReturn('2.0');

        return $cloudEvent;
    }

    private function createV1CloudEventInterface(): CloudEventInterface&MockObject
    {
        return $this->createMock(CloudEventInterface::class);
    }

    private function createSerializerWithSerializeCall(): SerializerInterface
    {
        $serializer = $this->createSerializerInterface();

        $serializer->expects($this->once())
            ->method('serialize')
            ->willReturn('{"id": 12345}');

        return $serializer;
    }

    private function createSerializerInterface(): SerializerInterface&MockObject
    {
        return $this->createMock(SerializerInterface::class);
    }
}