<?php

namespace Messenger\Unit\Messenger\CloudEvents\Factory;

use CloudEvents\V1\CloudEventInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Factory\CloudEventToMessageConverter;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\DummyEvent;
use JMS\Serializer\SerializerInterface;

class CloudEventToMessageConverterTest extends TestCase
{
    #[Test]
    public function aCloudEventIsConvertedToMessageObject(): void
    {
        $type = 'dummy-event';
        $data = [
            'id' => '200',
            'name' => 'lalala',
        ];

        $cloudEventToMessageConverter = $this->getCloudEventToMessageConverter(
            $this->createMessageRegistryWithGetEventClassForName($type, DummyEvent::class),
            $this->createSerializerWithDeserializeCall(
                json_encode($data, true),
                DummyEvent::class,
                'json',
                new DummyEvent('200', 'lalala')
            )
        );

        /** @var DummyEvent $message */
        $message = $cloudEventToMessageConverter->convert(
            $this->createV1CloudEventWithDataAndTypeAndContentType($data, $type)
        );

        self::assertInstanceOf(DummyEvent::class, $message);
        self::assertSame('200', $message->id);
        self::assertSame('lalala', $message->name);
    }

    private function getCloudEventToMessageConverter(
        MessageRegistryInterface $messageRegistry,
        SerializerInterface $serializer
    ): CloudEventToMessageConverter
    {
        return new CloudEventToMessageConverter(
            $messageRegistry,
            $serializer
        );
    }

    private function createV1CloudEventWithDataAndTypeAndContentType(array $data, string $type): CloudEventInterface
    {
        $cloudEvent = $this->createV1CloudEventInterface();

        $cloudEvent->expects($this->any())
            ->method('getData')
            ->willReturn($data);

        $cloudEvent->expects($this->any())
            ->method('getType')
            ->willReturn($type);

        return $cloudEvent;
    }

    private function createV1CloudEventInterface(): CloudEventInterface&MockObject
    {
        return $this->createMock(CloudEventInterface::class);
    }

    private function createMessageRegistryWithGetEventClassForName(string $name, string $messageClass): MessageRegistryInterface
    {
        $messageRegistry = $this->createMessageRegistryInterface();

        $messageRegistry->expects($this->once())
            ->method('getMessageClassNameForType')
            ->with($name)
            ->willReturn($messageClass);

        return $messageRegistry;
    }

    private function createMessageRegistryInterface(): MessageRegistryInterface&MockObject
    {
        return $this->createMock(MessageRegistryInterface::class);
    }

    private function createSerializerWithDeserializeCall(
        string $data,
        string $type,
        string $format,
        object $result
    ): SerializerInterface
    {
        $serializer = $this->createSerializerInterface();

        $serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($data, $type, $format)
            ->willReturn($result);

        return $serializer;
    }

    private function createSerializerInterface(): SerializerInterface&MockObject
    {
        return $this->createMock(SerializerInterface::class);
    }
}