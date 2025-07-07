<?php

namespace Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents;

use CloudEvents\CloudEventInterface;
use CloudEvents\V1\CloudEventTrait;
use DateTimeImmutable;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Factory\CloudEventFactoryInterface;
use Stegeman\Messenger\CloudEvents\Normalizer\NormalizerInterface;
use Stegeman\Messenger\CloudEvents\Serializer\CloudEventsSerializer;
use Symfony\Component\Messenger\Envelope;

class CloudEventsSerializerTest extends TestCase
{
    #[Test]
    public function anEventIsSerializedInCloudEventsFormat(): void
    {
        $dummyEvent = new DummyEvent(
            'id',
            'name'
        );
        $envelope = new Envelope($dummyEvent);

        $serializer = new CloudEventsSerializer(
            cloudEventFactory: $this->createCloudEventFactoryWithBuildForEventCall(),
            normalizer: $this->createNormalizerWithNormalizeCall(),
            serializer: $this->createSerializerWithSerializeCall()
        );

        $encodedEnvelope = $serializer->encode($envelope);

        self::assertSame(
            [
                'body' => '{ "just-an-array": "not the responsibility of this class"}',
                'headers' => []
            ],
            $encodedEnvelope
        );
    }

    private function createCloudEventFactoryWithBuildForEventCall(): CloudEventFactoryInterface
    {
        $cloudEventFactory = $this->createCloudEventFactoryInterface();

        $cloudEventFactory->expects($this->atLeastOnce())
            ->method('buildForEnvelope')
            ->willReturn($this->createCloudEventInterface());

        return $cloudEventFactory;
    }

    private function createCloudEventFactoryInterface(): CloudEventFactoryInterface&MockObject
    {
        return $this->createMock(CloudEventFactoryInterface::class);
    }

    private function createCloudEventInterface(): CloudEventInterface
    {
        return new class(
            id: 'f1aef6eabe3b67bbb9eb00e287d66650',
            source: 'source',
            type: 'type',
            dataContentType: 'application/json',
            data: new DummyEvent(id: 'id', name: 'name'),
            time: new DateTimeImmutable(),
        ) implements CloudEventInterface {
            use CloudEventTrait;
        };
    }

    private function createNormalizerWithNormalizeCall(): NormalizerInterface
    {
        $normalizer = $this->createNormalizerInterface();

        $normalizer->expects($this->once())
            ->method('normalize')
            ->with($this->isInstanceOf(CloudEventInterface::class))
            ->willReturn(['just-an-array' => 'not the responsibility of this class']);

        return $normalizer;
    }

    private function createNormalizerInterface(): NormalizerInterface&MockObject
    {
        return $this->createMock(NormalizerInterface::class);
    }

    private function createSerializerWithSerializeCall(): SerializerInterface
    {
        $serializer = $this->createSerializerInterface();

        $serializer->expects($this->once())
            ->method('serialize')
            ->willReturn('{ "just-an-array": "not the responsibility of this class"}');

        return $serializer;
    }

    private function createSerializerInterface(): SerializerInterface&MockObject
    {
        return $this->createMock(SerializerInterface::class);
    }
}