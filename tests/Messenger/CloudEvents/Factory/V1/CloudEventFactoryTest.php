<?php

namespace App\Tests\Messenger\CloudEvents\Factory\V1;

use PHPUnit\Framework\MockObject\MockObject;
use Stegeman\Messenger\CloudEvents\Factory\V1\CloudEventFactory;
use CloudEvents\CloudEventInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Stegeman\Messenger\CloudEvents\Serializer\IdGeneratorInterface;
use Symfony\Component\Messenger\Envelope;
use Tests\Stegeman\Messenger\CloudEvents\DummyEvent;

class CloudEventFactoryTest extends TestCase
{
    #[Test]
    public function aCloudEventCanBeCreated(): void
    {
        $messengerCloudEventFactory = $this->getMessengerCloudEventFactory(
            $this->createIdGeneratorWithGenerateCall(),
            $this->createMessageRegistryWithGetNameForEventCall()
        );

        $cloudEvent = $messengerCloudEventFactory->buildForEnvelope($this->createEnvelope());

        self::assertInstanceOf(CloudEventInterface::class, $cloudEvent);
    }

    private function getMessengerCloudEventFactory(
        IdGeneratorInterface $idGenerator,
        MessageRegistryInterface $messageRegistry
    ): CloudEventFactory
    {
        return new CloudEventFactory(
            idGenerator: $idGenerator,
            messageRegistry: $messageRegistry,
            format: 'application/json'
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