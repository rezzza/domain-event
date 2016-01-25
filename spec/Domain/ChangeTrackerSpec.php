<?php

namespace spec\Rezzza\DomainEvent\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Rezzza\DomainEvent\Domain\DomainEvent;
use Rezzza\DomainEvent\Domain\EventBus;
use Rezzza\DomainEvent\Domain\AggregateRoot;

class ChangeTrackerSpec extends ObjectBehavior
{
    function it_is_initializable(EventBus $eventBus)
    {
        $this->beConstructedWith($eventBus);
        $this->shouldHaveType('Rezzza\DomainEvent\Domain\ChangeTracker');
    }

    function it_publish_changes_found_on_aggregate(EventBus $eventBus, AggregateRoot $aggregate, DomainEvent $change)
    {
        $this->beConstructedWith($eventBus);
        $aggregate->getChanges()->willReturn([$change]);
        $aggregate->clearChanges()->willReturn();
        $eventBus->publish($change)->shouldBeCalled();
        $this->track($aggregate);
    }

    function it_does_not_publish_anything_if_no_change_found(EventBus $eventBus, AggregateRoot $aggregate)
    {
        $this->beConstructedWith($eventBus);
        $aggregate->getChanges()->willReturn([]);
        $aggregate->clearChanges()->willReturn();
        $eventBus->publish()->shouldNotBeCalled();
        $this->track($aggregate);
    }
}
