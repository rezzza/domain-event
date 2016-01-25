<?php

namespace Rezzza\DomainEvent\Domain;

class EventProperties
{
    private $properties;

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function getValues()
    {
        return $this->properties;
    }

    public static function fromEvent(DomainEvent $event)
    {
        $refEvent = new \ReflectionClass($event);
        $refEventProperties = $refEvent->getProperties();
        $properties = [];
        $convertPropertyValueAsString = function ($propertyValue) {
            if (true === is_array($propertyValue)) {
                return implode(', ', $propertyValue);
            }

            if ($propertyValue instanceof \DateTimeInterface) {
                return $propertyValue->format('Y-m-d H:i:s');
            }

            return (string) $propertyValue;
        };

        foreach ($refEventProperties as $property) {
            $propertyName = $property->getName();
            $getter = 'get'.ucfirst($propertyName);
            if (false === method_exists($event, $getter)) {
                throw new \LogicException(sprintf('Each event property should have a getter. Missing %s::%s', get_class($event), $getter));
            }
            $propertyValue = $event->{$getter}();
            $properties[$propertyName] = $convertPropertyValueAsString($propertyValue);
        }

        return new EventProperties($properties);
    }
}
