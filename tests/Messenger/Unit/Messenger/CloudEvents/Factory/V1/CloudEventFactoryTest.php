<?php

namespace Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\Factory\V1;

use CloudEvents\CloudEventInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Factory\V1\CloudEventFactory;
use Stegeman\Messenger\CloudEvents\Serializer\IdGeneratorInterface;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Symfony\Component\Messenger\Envelope;
use Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\DummyEvent;

class CloudEventFactoryTest extends TestCase
{
    #[Test]
    public function aCloudEventCanBeCreated(): void
    {
        $messengerCloudEventFactory = $this->getMessengerCloudEventFactory(
            $this->createIdGeneratorWithGenerateCall(),
            $this->createMessageRegistryWithGetNameForEventCall()
        );

        $cloudEvent = $messengerCloudEventFactory->buildForEnvelope($this->createEnvelope(), 'application/json');

        self::assertInstanceOf(CloudEventInterface::class, $cloudEvent);
    }

    private function getMessengerCloudEventFactory(
        IdGeneratorInterface $idGenerator,
        MessageRegistryInterface $messageRegistry
    ): CloudEventFactory
    {
        return new CloudEventFactory(
            idGenerator: $idGenerator,
            messageRegistry: $messageRegistry
        );
    }

    private function createEnvelope(): Envelope
    {
        return new Envelope(
            new DummyEvent(id: '1', name: 'name')
        );
    }

    private function createIdGeneratorWithGenerateCall(): IdGeneratorInterface
    {
        $idGenerator = $this->createIdGeneratorInterface();

        $idGenerator->expects($this->once())
            ->method('generate')
            ->willReturn('f215e599-47ae-4bb1-84b9-0297d0fd2b0c');

        return $idGenerator;
    }

    private function createIdGeneratorInterface(): IdGeneratorInterface&MockObject
    {
        return $this->createMock(IdGeneratorInterface::class);
    }

    private function createMessageRegistryWithGetNameForEventCall(): MessageRegistryInterface
    {
        $messageRegistry = $this->createMessageRegistryInterface();
        $messageRegistry->expects($this->once())
            ->method('getNameForMessage')
            ->with($this->isInstanceOf(DummyEvent::class))
            ->willReturn('dummy_event');

        return $messageRegistry;
    }

    private function createMessageRegistryInterface(): MessageRegistryInterface&MockObject
    {
        return $this->createMock(MessageRegistryInterface::class);
    }
}