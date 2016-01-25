<?php

namespace Rezzza\DomainEvent\Infra\Messaging\Redis;

use Rezzza\DomainEvent\Domain\EventJsonSerializable;

abstract class RedisEventListener
{
    protected $redis;

    protected $scope;

    protected $listenedEvent;

    public function __construct($redis, $scope, $listenedEvent)
    {
        RedisClient::guardValid($redis);
        $this->redis = $redis;
        $this->channel = new EventChannel($scope);
        $this->listenedEvent = $listenedEvent;
    }

    final public function registerConsumer()
    {
        $this->redis->setOption(\Redis::OPT_READ_TIMEOUT, -1);
        $this->redis->subscribe([(string) $this->channel], [$this, 'onEvent']);
    }

    final public function onEvent($redis, $chan, $msg)
    {
        $event = EventJsonSerializable::fromJson($msg);

        if (false === $event->isNamed($this->listenedEvent)) {
            return;
        }

        $this->execute($event);
    }

    abstract public function execute(EventJsonSerializable $event);
}
