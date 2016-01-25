<?php

namespace Rezzza\DomainEvent\Infra\Persistence\Doctrine;

use Doctrine\Common\Persistence\ManagerRegistry;
use Rezzza\DomainEvent\Domain\AggregateRoot;
use Rezzza\DomainEvent\Domain\ChangeTracker;

abstract class ORMAggregateRootRepository extends ORMRepository
{
    /** @var ChangeTracker */
    private $changeTracker;

    /**
     * @param ManagerRegistry $doctrine
     * @param string $entityClassName
     * @param ChangeTracker $changeTracker
     */
    public function __construct(ManagerRegistry $doctrine, $entityClassName, ChangeTracker $changeTracker)
    {
        parent::__construct($doctrine, $entityClassName);
        $this->changeTracker = $changeTracker;
    }

    public function track(AggregateRoot $aggregate)
    {
        $this->changeTracker->track($aggregate);
    }
}
