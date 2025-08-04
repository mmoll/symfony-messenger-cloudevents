<?php

namespace Stegeman\Messenger\CloudEvents\Factory;

use CloudEvents\CloudEventInterface;
use CloudEvents\V1\CloudEventInterface as V1CloudEventInterface;
use JMS\Serializer\SerializerInterface;
use Stegeman\Messenger\CloudEvents\Serializer\MessageRegistryInterface;

readonly class CloudEventToMessageConverter implements CloudEventToMessageConverterInterface
{
    public function __construct(
        private MessageRegistryInterface $messageRegistry,
        private SerializerInterface $serializer
    ) {}
    public function convert(CloudEventInterface $cloudEvent): object
    {
        /** @var V1CloudEventInterface $cloudEvent */
        $messageClassName = $this->messageRegistry->getMessageClassNameForType($cloudEvent->getType());

        return $this->serializer->deserialize(json_encode($cloudEvent->getData()), $messageClassName, 'json');
    }
}