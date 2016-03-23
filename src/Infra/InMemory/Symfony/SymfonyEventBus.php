<?php

namespace Rezzza\DomainEvent\Infra\InMemory\Symfony;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Rezzza\DomainEvent\Domain\DomainEvent;
use Rezzza\DomainEvent\Domain\EventBus;

class SymfonyEventBus implements EventBus
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(DomainEvent $event)
    {
        $this->eventDispatcher->dispatch($event->getEventName(), $event);
    }
}
