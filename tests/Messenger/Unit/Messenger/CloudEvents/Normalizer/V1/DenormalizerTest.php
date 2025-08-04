<?php

namespace Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\V1\CloudEventInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Normalizer\V1\Denormalizer;
use CloudEvents\Serializers\Normalizers\V1\DenormalizerInterface;
use Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\DummyEvent;

class DenormalizerTest extends TestCase
{
    #[Test]
    public function  anArrayIsDenormalizedToCloudEvent(): void
    {
        $input = [ 'body' => '{"id": "100", "name": "foobar"}'];

        $denormalizer = new Denormalizer(
            $this->createDenormalizerWithDenormalizeCall($input, new DummyEvent("100", "foobar"))
        );

        $cloudEvent = $denormalizer->denormalize($input); // Input is not important. The real lifting is done by the

        self::assertInstanceOf(CloudEventInterface::class, $cloudEvent);
    }

    private function createDenormalizerWithDenormalizeCall(array $denormalizeInput, object $denormalizeResult): DenormalizerInterface
    {
        $denormalizer = $this->createSdkDenormalizerInterface();

        $denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($denormalizeInput)
            ->willReturn($this->createCloudEventWithData($denormalizeResult));

        return $denormalizer;
    }

    private function createSdkDenormalizerInterface(): DenormalizerInterface&MockObject
    {
        return $this->createMock(DenormalizerInterface::class);
    }

    private function createCloudEventWithData(object $data): CloudEventInterface
    {
        $cloudEvent = $this->createMock(CloudEventInterface::class);

        $cloudEvent->expects($this->any())
            ->method('getData')
            ->willReturn($data);

        return $cloudEvent;
    }
}