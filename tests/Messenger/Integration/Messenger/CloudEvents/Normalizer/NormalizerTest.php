<?php

namespace Stegeman\Tests\Messenger\Integration\Messenger\CloudEvents\Normalizer;

use CloudEvents\Serializers\Normalizers\V1\Normalizer as SdkNormalizer;
use CloudEvents\V1\CloudEvent;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Normalizer\V1\Normalizer;
use Stegeman\Tests\Messenger\Unit\Messenger\CloudEvents\DummyEvent;

class NormalizerTest extends TestCase
{
    #[Test]
    public function aCloudEventIsNormalized(): void
    {
        $normalizer = $this->getNormalizer();

        $result = $normalizer->normalize(
            new CloudEvent(
                '100',
                'name-of-producer',
                'nl.stegeman.dummy-event-name',
                new DummyEvent('dummy-event-id', 'dummy-event-name'),
                'application/json',
                'v1.0',
                "subject",
                $time = new \DateTimeImmutable(),
                []
            )
        );

        self::assertEquals(
            array(
                "specversion"=> "1.0",
                "id"=> "100",
                "source"=> "name-of-producer",
                "type"=>  "nl.stegeman.dummy-event-name",
                "datacontenttype"=>  "application/json",
                "dataschema"=>  "v1.0",
                "subject"=> "subject",
                "time"=> $time->format('Y-m-d\TH:i:s\Z'),
                "data"=> new DummyEvent('dummy-event-id', 'dummy-event-name'),
            ),
            $result
        );
    }

    private function getNormalizer(): Normalizer
    {
        return new Normalizer(
            new SdkNormalizer()
        );
    }
}