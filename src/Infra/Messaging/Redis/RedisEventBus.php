<?php

namespace Rezzza\DomainEvent\Infra\Messaging\Redis;

use Rezzza\DomainEvent\Domain\EventBus;
use Rezzza\DomainEvent\Domain\DomainEvent;
use Rezzza\DomainEvent\Domain\EventJsonSerializable;

class RedisEventBus implements EventBus
{
    private $redis;

    /** @var string */
    private $channel;

    public function __construct($redis, $scope)
    {
        RedisClient::guardValid($redis);
        $this->redis = $redis;
        $this->channel = new EventChannel($scope);
    }

    /**
     * {@inheritdoc}
     */
    public function publish(DomainEvent $event)
    {
        $this->redis->publish(
            (string) $this->channel,
            json_encode(EventJsonSerializable::fromDomainEvent($event))
        );
    }
}
