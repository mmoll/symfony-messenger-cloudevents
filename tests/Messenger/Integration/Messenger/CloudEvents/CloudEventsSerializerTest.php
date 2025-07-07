<?php

namespace Stegeman\Tests\Messenger\Integration\Messenger\CloudEvents;

use CloudEvents\Serializers\Normalizers\V1\Normalizer as SdkNormalizer;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidFactory;
use Stegeman\Messenger\CloudEvents\Factory\V1\CloudEventFactory;
use Stegeman\Messenger\CloudEvents\Normalizer\V1\Normalizer;
use Stegeman\Messenger\CloudEvents\Serializer\CloudEventsSerializer;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;
use Stegeman\Messenger\CloudEvents\Serializer\UuidGenerator;
use Symfony\Component\Messenger\Envelope;

class CloudEventsSerializerTest extends TestCase
{
    #[Test]
    public function anEventIsSerialized(): void
    {
        $cloudEventsSerializer = new CloudEventsSerializer(
            new CloudEventFactory(
                new UuidGenerator(
                    new UuidFactory()
                ),
                $this->createMessageRegistry(),
                'application/json'
            ),
            new Normalizer(
                new SdkNormalizer()
            ),
            SerializerBuilder::create()->build()
        );

        $serializedEvent = $cloudEventsSerializer->encode(new Envelope(new DummyEvent('100', 'lalala')));

        self::assertTrue((bool) json_decode($serializedEvent['body']));
    }

    private function createMessageRegistry(): MessageRegistryInterface
    {
        return new class () implements MessageRegistryInterface {

            public function getNameForMessage(object $message): string
            {
                return 'nl.stegeman.dummy-message';
            }
        };
    }
}