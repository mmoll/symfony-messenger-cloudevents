<?php

namespace Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\Normalizer\V1;

use CloudEvents\Serializers\Normalizers\V1\NormalizerInterface;
use CloudEvents\V1\CloudEventInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Normalizer\V1\Normalizer;

class NormalizerTest extends TestCase
{
    #[Test]
    public function cloudEventIsNormalizedToArray(): void
    {
        $normalizer = $this->getNormalizer(
            $this->createNormalizerWithNormalizeCall()
        );

        $result = $normalizer->normalize($this->createCloudEventWithData());


        self::assertIsArray($result);
        self::assertSame(
            [
                'type' => 'message-name',
                'body' => '{"id": 12345}'
            ],
            $result
        );
    }

    private function getNormalizer(NormalizerInterface $normalizer): Normalizer
    {
        return new Normalizer($normalizer);
    }

    private function createCloudEventWithData(): CloudEventInterface
    {
        $cloudEvent = $this->createV1CloudEventInterface();

        $cloudEvent->expects($this->any())
            ->method('getId')
            ->willReturn('12345');

        return $cloudEvent;
    }

    private function createV1CloudEventInterface(): CloudEventInterface&MockObject
    {
        return $this->createMock(CloudEventInterface::class);
    }

    private function createNormalizerWithNormalizeCall(): NormalizerInterface
    {
        $normalizer = $this->createSdkNormalizerInterface();

        $normalizer->expects($this->once())
            ->method('normalize')
            ->willReturn([
                'type' => 'message-name',
                'body' => '{"id": 12345}'
            ]);

        return $normalizer;
    }

    private function createSdkNormalizerInterface(): NormalizerInterface&MockObject
    {
        return $this->createMock(NormalizerInterface::class);
    }
}