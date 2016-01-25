<?php

namespace spec\Rezzza\DomainEvent\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Rezzza\DomainEvent\Tests\Fixtures\FixtureEvent;

class EventPropertiesSpec extends ObjectBehavior
{
    function it_is_initializable_from_domain_event()
    {
        $event = new FixtureEvent(42, new \DateTime('2016-01-05 16:31:00'));
        $this->beConstructedThrough('fromEvent', [$event]);
        $this->shouldHaveType('Rezzza\DomainEvent\Domain\EventProperties');
        $this->getValues()->shouldBeEqualTo([
            'entityId' => '42',
            'createdAt' => '2016-01-05 16:31:00'
        ]);
    }
}
