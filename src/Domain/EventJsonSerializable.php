<?php

namespace Rezzza\DomainEvent\Domain;

class EventJsonSerializable implements \JsonSerializable
{
    /** @var string */
    private $eventName;

    /** @var array */
    private $eventProperties;

    public function __construct($eventName, array $eventProperties)
    {
        if (false === is_string($eventName)) {
            throw new \LogicException('Event name should be a string');
        }

        $this->eventName = $eventName;
        $this->eventProperties = $eventProperties;
    }

    public static function fromDomainEvent(DomainEvent $event)
    {
        $eventProperties = EventProperties::fromEvent($event);

        return new EventJsonSerializable($event->getEventName(), $eventProperties->getValues());
    }

    public static function fromJson($json)
    {
        $jsonDecoded = json_decode($json, true);

        if (false === is_array($jsonDecoded)) {
            throw new \LogicException(sprintf('Wrong json provided : %s. Error : %s', $json, json_last_error_msg()));
        }

        if (false === array_key_exists('name', $jsonDecoded) || false === array_key_exists('properties', $jsonDecoded)) {
            throw new \LogicException('Event serialized should be composed of "name" and "properties" keys');
        }

        return new EventJsonSerializable($jsonDecoded['name'], $jsonDecoded['properties']);
    }

    public function getProperties()
    {
        return $this->eventProperties;
    }

    public function isNamed($name)
    {
        return $this->eventName === $name;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->eventName,
            'properties' => $this->eventProperties
        ];
    }
}
