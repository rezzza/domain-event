<?php

namespace Rezzza\DomainEvent\Tests\Fixtures;

use Rezzza\DomainEvent\Domain\DomainEvent;

class FixtureEvent implements DomainEvent
{
    private $entityId;

    private $createdAt;

    public function __construct($entityId, $createdAt)
    {
        $this->entityId = $entityId;
        $this->createdAt = $createdAt;
    }

    public function getEntityId()
    {
        return $this->entityId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getEventName()
    {
        return 'it_happened';
    }
}
