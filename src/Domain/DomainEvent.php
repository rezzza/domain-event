<?php

namespace Rezzza\DomainEvent\Domain;

interface DomainEvent
{
    public function getEventName();
}
