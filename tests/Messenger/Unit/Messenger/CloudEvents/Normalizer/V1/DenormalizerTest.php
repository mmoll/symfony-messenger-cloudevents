<?php

namespace Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\V1\CloudEventInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Normalizer\V1\Denormalizer;
use CloudEvents\Serializers\Normalizers\V1\DenormalizerInterface;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\DummyEvent;
use Symfony\Component\Serializer\SerializerInterface;

class DenormalizerTest extends TestCase
{
    #[Test]
    public function anArrayIsDenormalizedToCloudEvent(): void
    {
        $messageType = 'event.test-event';
        $messageData = json_encode(['id' => '100', 'name' => 'foobar']);
        $expectedMessageType = DummyEvent::class;

        $input = [
            'body' => sprintf(
                '{
                    "specversion": "1.0",
                    "id": "82c6e8b2-8f53-44e1-a083-9da4d20e3fcb",
                    "source": "event.test-event",
                    "type": "%s",
                    "datacontenttype": "application/json",
                    "data": %s
                }',
                $messageType,
                $messageData
            ),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];

        $sdkDenormalizerInput = [
            'specversion' => '1.0',
            'id' => '82c6e8b2-8f53-44e1-a083-9da4d20e3fcb',
            'source' => 'event.test-event',
            'type' => 'event.test-event',
            'datacontenttype' => 'application/json',
            'data' => new DummyEvent('100', 'foobar')
        ];

        $denormalizer = $this->getDenormalizer(
            $this->createMessageRegistryWithGetMessageClassNameForTypeCall($messageType, $expectedMessageType),
            $this->createSerializerWithDeserializeCall($messageData, new $expectedMessageType("100", "foobar")),
            $this->createDenormalizerWithDenormalizeCall($sdkDenormalizerInput)
        );

        /** @var CloudEventInterface $cloudEvent */
        $cloudEvent = $denormalizer->denormalize($input);

        self::assertInstanceOf(CloudEventInterface::class, $cloudEvent);
    }

    private function getDenormalizer(
        MessageRegistryInterface $messageRegistry,
        SerializerInterface $serializer,
        DenormalizerInterface $denormalizer
    ): Denormalizer
    {
        return new Denormalizer(
            $messageRegistry,
            $serializer,
            $denormalizer
        );
    }

    private function createMessageRegistryWithGetMessageClassNameForTypeCall(string $type, string $messageClassName): MessageRegistryInterface
    {
        $messageRegistry = $this->createMessageRegistryInterface();

        $messageRegistry->expects($this->once())
            ->method('getMessageClassNameForType')
            ->with($type)
            ->willReturn($messageClassName);

        return $messageRegistry;
    }

    private function createMessageRegistryInterface(): MessageRegistryInterface&MockObject
    {
        return $this->createMock(MessageRegistryInterface::class);
    }

    private function createSerializerWithDeserializeCall(string $messageData, object $deserializeResult): SerializerInterface
    {
        $serializer = $this->createSerializerInterface();

        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($messageData)
            ->willReturn($deserializeResult);

        return $serializer;
    }

    private function createSerializerInterface(): SerializerInterface&MockObject
    {
        return $this->createMock(SerializerInterface::class);
    }

    private function createDenormalizerWithDenormalizeCall(array $denormalizeInput): DenormalizerInterface
    {
        $denormalizer = $this->createDenormalizerInterface();

        $denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($denormalizeInput);

        return $denormalizer;
    }

    private function createDenormalizerInterface(): DenormalizerInterface&MockObject
    {
        return $this->createMock(DenormalizerInterface::class);
    }


}
