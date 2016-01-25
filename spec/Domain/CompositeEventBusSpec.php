<?php

namespace spec\Rezzza\DomainEvent\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Rezzza\DomainEvent\Domain\DomainEvent;
use Rezzza\DomainEvent\Domain\EventBus;

class CompositeEventBusSpec extends ObjectBehavior
{
    function it_should_be_run_with_one_delegate_bus_at_least()
    {
        $this->shouldThrow('\LogicException')->during('__construct', [[]]);
    }

    function it_publish_event_on_all_delegate_bus(EventBus $eventBus1, EventBus $eventBus2, DomainEvent $event)
    {
        $this->beConstructedWith([$eventBus1, $eventBus2]);
        $eventBus1->publish($event)->shouldBeCalled();
        $eventBus2->publish($event)->shouldBeCalled();
        $this->publish($event);
    }
}
