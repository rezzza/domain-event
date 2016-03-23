<?php

require_once __DIR__.'/../vendor/autoload.php';

use Rezzza\DomainEvent\Domain\EventJsonSerializable;
use Rezzza\DomainEvent\Infra\Messaging\Redis\RedisEventListener;

$redis = new Redis();
$redis->connect('127.0.0.1');

class RedisPrintVoucherListener extends RedisEventListener
{
    public function execute(EventJsonSerializable $event)
    {
        echo sprintf('[Redis] booking#%s has been confirmed. We need to print a voucher.'.PHP_EOL, $event->getProperties()['bookingId']);
    }
}

$listener = new RedisPrintVoucherListener($redis, 'booking_engine', 'booking.confirmed');
$listener->registerConsumer();
