<?php

namespace Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\Factory\V1;

use CloudEvents\CloudEventInterface;
use CloudEvents\V1\CloudEvent;
use CloudEvents\V1\CloudEventInterface as V1CloudEventInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Factory\V1\CloudEventFactory;
use Stegeman\Messenger\CloudEvents\Normalizer\DenormalizerInterface;
use Stegeman\Messenger\CloudEvents\Serializer\IdGeneratorInterface;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Symfony\Component\Messenger\Envelope;
use Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\DummyEvent;

class CloudEventFactoryTest extends TestCase
{
    #[Test]
    public function aCloudEventCanBeCreatedFromBasicEvent(): void
    {
        $messengerCloudEventFactory = $this->getMessengerCloudEventFactory(
            $this->createIdGeneratorWithGenerateCall(),
            $this->createMessageRegistryWithGetNameForEventCall(),
            $this->createDenormalizerInterface()
        );

        /** @var CloudEvent $cloudEvent */
        $cloudEvent = $messengerCloudEventFactory->buildForEnvelope($this->createEnvelope(), 'application/json');

        self::assertInstanceOf(CloudEventInterface::class, $cloudEvent);
        self::assertSame('dummy_event', $cloudEvent->getType());
    }

    #[Test]
    public function aCloudEventCanBeCreatedBasedOnSerializedPayload(): void
    {
        $this->markTestSkipped(
            sprintf(
                '%s:%d: First make it work. Then refactor cloud event creation by payload to CloudEventFactory',
                __FILE__,
                __LINE__
            )
        );

        $input = [
            'specversion' => '1.0',
            'id' => 'd4576019-8919-42c6-ba46-4f82fcbfc94d',
            'source' => 'nl.stegeman.dummy-message',
            'type' => 'nl.stegeman.dummy-message',
            'datacontenttype' => 'application/json',
            'time' => '2025-07-14T13:07:00Z',
            'data' => [
                'id' => '100',
                'name' => 'lalala',
            ]
        ];

        $expectedResult = new DummyEvent('200', 'lalalaa');
        $denormalizerResult = $this->createCloudEventWithData(["id" => "200", "name" => "lalala"]);

        $messengerCloudEventFactory = $this->getMessengerCloudEventFactory(
            $this->createIdGeneratorInterface(),
            $this->createMessageRegistryInterface(),
            $this->createDenormalizerWithDenormalizeCall(
                $input,
                $denormalizerResult
            )
        );

        self::assertSame($expectedResult, $messengerCloudEventFactory->buildFromPayload($input)->getData());
    }

    private function getMessengerCloudEventFactory(
        IdGeneratorInterface $idGenerator,
        MessageRegistryInterface $messageRegistry,
        DenormalizerInterface $denormalizer
    ): CloudEventFactory
    {
        return new CloudEventFactory(
            idGenerator: $idGenerator,
            messageRegistry: $messageRegistry,
            denormalizer: $denormalizer
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

    private function createMessageRegistryWithGetEventClassForNameCall(): MessageRegistryInterface
    {
        $messageRegistry = $this->createMessageRegistryInterface();

        $messageRegistry->expects($this->once())
            ->method('getMessageClassNameForType')
            ->willReturn(DummyEvent::class);

        return $messageRegistry;
    }

    private function createMessageRegistryWithGetNameForEventCall(): MessageRegistryInterface
    {
        $messageRegistry = $this->createMessageRegistryInterface();
        $messageRegistry->expects($this->once())
            ->method('getTypeForMessageClass')
            ->with($this->isInstanceOf(DummyEvent::class))
            ->willReturn('dummy_event');

        return $messageRegistry;
    }

    private function createMessageRegistryInterface(): MessageRegistryInterface&MockObject
    {
        return $this->createMock(MessageRegistryInterface::class);
    }

    private function createDenormalizerInterface(): DenormalizerInterface&MockObject
    {
        return $this->createMock(DenormalizerInterface::class);
    }

    private function createDenormalizerWithDenormalizeCall(array $input, CloudEventInterface $denormalizationResult)
    {
        $denormalizer = $this->createDenormalizerInterface();

        $denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($input)
            ->willReturn($denormalizationResult);

        return $denormalizer;
    }

    private function createCloudEventWithData(array $data): V1CloudEventInterface
    {
        $cloudEvent = $this->createMock(\CloudEvents\V1\CloudEventInterface::class);

        $cloudEvent->expects($this->any())
            ->method('getData')
            ->willReturn($data);

        return $cloudEvent;
    }
}