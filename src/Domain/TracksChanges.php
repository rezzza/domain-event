<?php

namespace Rezzza\DomainEvent\Domain;

/**
 * Trait is useful to avoid complication by inheritance when entity already extends another doctrine class
 */
trait TracksChanges
{
    /** @var DomainEvent[] */
    private $changes = [];

    /**
     * @return DomainEvent[]
     */
    public function getChanges()
    {
        return $this->changes;
    }

    public function clearChanges()
    {
        $this->changes = [];
    }

    protected function recordChange(DomainEvent $event)
    {
        $this->changes[] = $event;
    }
}
