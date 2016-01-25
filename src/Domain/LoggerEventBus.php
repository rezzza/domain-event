<?php

namespace Rezzza\DomainEvent\Domain;

use Psr\Log\LoggerInterface;

class LoggerEventBus implements EventBus
{
    /** @var Psr\Log\LoggerInterface */
    private $logger;

    /** @var EventBus */
    private $delegateEventBus;

    public function __construct(LoggerInterface $logger, EventBus $delegateEventBus)
    {
        $this->logger = $logger;
        $this->delegateEventBus = $delegateEventBus;
    }

    public function publish(DomainEvent $event)
    {
        $eventProperties = EventProperties::fromEvent($event);

        $this->logger->info(
            get_class($event),
            $eventProperties->getValues()
        );

        $this->delegateEventBus->publish($event);
    }
}
