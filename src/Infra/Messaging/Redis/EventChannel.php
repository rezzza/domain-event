<?php

namespace Rezzza\DomainEvent\Infra\Messaging\Redis;

class EventChannel
{
    private $scope;

    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    public function __toString()
    {
        return sprintf('event_bus:%s', $this->scope);
    }
}
