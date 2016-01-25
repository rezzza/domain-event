<?php

namespace Rezzza\DomainEvent\Domain;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
