<?php

namespace Rezzza\DomainEvent\Domain;

class ChangeTracker
{
    private $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function track(AggregateRoot $aggregateRoot)
    {
        $recordedChanges = $aggregateRoot->getChanges();
        $aggregateRoot->clearChanges();

        foreach ($recordedChanges as $event) {
            $this->eventBus->publish($event);
        }
    }
}
