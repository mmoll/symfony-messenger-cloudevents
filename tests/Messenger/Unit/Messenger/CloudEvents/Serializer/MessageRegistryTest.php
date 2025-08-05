<?php

namespace Messenger\Unit\Messenger\CloudEvents\Serializer;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistry;
use Stegeman\Messenger\CloudEvents\Serializer\NoNameFoundForMessageException;

class MessageRegistryTest extends TestCase
{
    #[Test]
    public function theNameOfAMessageCanBeFoundByItsClassName(): void
    {
        $messageRegistry = new MessageRegistry();

        $messageRegistry->addMessage('name', \stdClass::class);

        self::assertSame('name', $messageRegistry->getTypeForMessageClass(new \stdClass()));
    }

    #[Test]
    public function anExceptionIsThrownIfNoNameCanBeFoundForGivenMessage(): void
    {
        self::expectException(NoNameFoundForMessageException::class);
        self::expectExceptionMessage('No name found for message of type stdClass');

        $messageRegistry = new MessageRegistry();

        $messageRegistry->getTypeForMessageClass(new \stdClass());
    }
}