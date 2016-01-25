<?php

namespace Rezzza\DomainEvent\Domain;

/**
 * Allow to publish event on multiple bus
 */
class CompositeEventBus implements EventBus
{
    private $eventBuses;

    public function __construct(array $eventBuses)
    {
        if (count($eventBuses) < 1) {
            throw new \LogicException('You should use at least one EventBus to run a CompositeEventBus');
        }

        foreach ($eventBuses as $eventBus) {
            $this->addEventBus($eventBus);
        }
    }

    public function publish(DomainEvent $event)
    {
        foreach ($this->eventBuses as $eventBus) {
            $eventBus->publish($event);
        }
    }

    private function addEventBus(EventBus $eventBus)
    {
        $this->eventBuses[] = $eventBus;
    }
}
