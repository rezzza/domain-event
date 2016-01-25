<?php

namespace Rezzza\DomainEvent\Domain;

interface EventBus
{
    public function publish(DomainEvent $event);
}
