# Cloudevents integation for Symfony Messenger

## Install

```composer require stegeman/symfony-messenger-cloudevents```

## Configuration

Configure the correct serializer for your transport:

```
        transports:
            async:
                ...
                serializer: 'Stegeman\Messenger\CloudEvents\Serializer\CloudEventsSerializer'
            ...
```

All set, go!
