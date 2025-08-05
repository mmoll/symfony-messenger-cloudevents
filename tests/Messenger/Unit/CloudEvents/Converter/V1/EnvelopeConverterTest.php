<?php

namespace Stegeman\Tests\Messenger\Unit\CloudEvents\Converter\V1;

use CloudEvents\V1\CloudEvent;
use CloudEvents\V1\CloudEventImmutable;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Converter\V1\EnvelopeConverter;
use Stegeman\Messenger\CloudEvents\Serializer\IdGeneratorInterface;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\DummyEvent;
use Symfony\Component\Messenger\Envelope;

class EnvelopeConverterTest extends TestCase
{
    #[Test]
    public function aCloudEventIsConvertedToAnEnvelope(): void
    {
        $type = 'dummy-event';
        $messageName = DummyEvent::class;
        $dummyEvent = new DummyEvent('300', 'lalala');
        $cloudEvent = new CloudEventImmutable('1', 'source', $type, $dummyEvent);

        $envelopeConverter = $this->getEnvelopeConverter(
            $this->createMessageRegistryWithTypeAndClassName($type, $messageName),
            $this->createIdGeneratorInterface()
        );
        $envelope = $envelopeConverter->fromCloudEvent($cloudEvent);

        self::assertInstanceOf(DummyEvent::class, $envelope->getMessage());
        self::assertSame('300', $envelope->getMessage()->id);
        self::assertSame('lalala', $envelope->getMessage()->name);
    }

    #[Test]
    public function anEnvelopeIsConvertedToACloudEvent(): void
    {
        $type = 'dummy-message';
        $messageName = DummyEvent::class;
        $cloudEventId = 'generated-value';

        $dummyEvent = new DummyEvent('100', 'foobar');
        $envelope = new Envelope($dummyEvent);

        $envelopeConverter = $this->getEnvelopeConverter(
            $this->createMessageRegistryWithTypeAndClassName($type, $messageName),
            $this->createIdGeneratorWithGenerateCall($envelope, $cloudEventId)
        );

        $cloudEvent = $envelopeConverter->toCloudEvent($envelope);

        self::assertInstanceOf(CloudEvent::class, $cloudEvent);
        self::assertSame($cloudEventId, $cloudEvent->getId());
        self::assertSame($type, $cloudEvent->getType());
        self::assertSame($dummyEvent, $cloudEvent->getData());
    }

    private function getEnvelopeConverter(
        MessageRegistryInterface $messageRegistry,
        IdGeneratorInterface $idGenerator
    ): EnvelopeConverter
    {
        return new EnvelopeConverter($messageRegistry, $idGenerator);
    }

    private function createMessageRegistryWithTypeAndClassName(string $type, string $className): MessageRegistryInterface
    {
        $messageRegistry = $this->createMock(MessageRegistryInterface::class);

        $messageRegistry->expects($this->any())
            ->method('getMessageClassNameForType')
            ->with($type)
            ->willReturn($className);

        $messageRegistry->expects($this->any())
            ->method('getTypeForMessageClass')
            ->with($className)
            ->willReturn($type);

        return $messageRegistry;
    }

    private function createIdGeneratorWithGenerateCall(Envelope $envelope, string $generatedValue): IdGeneratorInterface
    {
        $idGenerator = $this->createIdGeneratorInterface();

        $idGenerator->expects($this->any())
            ->method('generate')
            ->with($envelope)
            ->willReturn($generatedValue);

        return $idGenerator;
    }

    private function createIdGeneratorInterface(): IdGeneratorInterface&MockObject
    {
        return $this->createMock(IdGeneratorInterface::class);
    }
}