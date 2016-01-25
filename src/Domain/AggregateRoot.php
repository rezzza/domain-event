<?php

namespace Rezzza\DomainEvent\Domain;

interface AggregateRoot
{
    /**
     * @return DomainEvent[]
     */
    public function getChanges();

    public function clearChanges();
}
