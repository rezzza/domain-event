<?php

namespace Rezzza\DomainEvent\Infra\Messaging\Redis;

use Redis;
use Snc\RedisBundle\Client\Phpredis\ClientInterface as PhpRedisClientInterface;

class RedisClient
{
    public static function guardValid($redis)
    {
        if (!$redis instanceof PhpRedisClientInterface && !$redis instanceof Redis) {
            throw new \InvalidArgumentException(
                sprintf('Redis has to be an instanceof Snc\RedisBundle\Client\(Php|P)redis\ClientInterface or \Redis, here you give a %s', get_class($redis))
            );
        }
    }
}
