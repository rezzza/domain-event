<?php

require_once __DIR__.'/../vendor/autoload.php';

use Pimple\Container;
use Psr\Log\AbstractLogger;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Rezzza\DomainEvent\Domain\AggregateRoot;
use Rezzza\DomainEvent\Domain\ChangeTracker;
use Rezzza\DomainEvent\Domain\DomainEvent;
use Rezzza\DomainEvent\Domain\EventJsonSerializable;
use Rezzza\DomainEvent\Domain\TracksChanges;
use Rezzza\DomainEvent\Domain\CompositeEventBus;
use Rezzza\DomainEvent\Domain\LoggerEventBus;
use Rezzza\DomainEvent\Infra\InMemory\Symfony\SymfonyEventBus;
use Rezzza\DomainEvent\Infra\Messaging\Redis\RedisEventBus;

class EchoLogger extends AbstractLogger
{
    public function log($level, $message, array $context = array())
    {
        echo sprintf('%s [%s] %s : %s', (new \DateTime)->format(\DateTime::ISO8601), $level, $message, var_export($context, true)).PHP_EOL;
    }
}

/**
 * For now extends Symfony event to use EventDispatcher... Should find a solution to avoid it
 */
class BookingConfirmed extends Event implements DomainEvent
{
    private $bookingId;

    public function __construct($bookingId)
    {
        $this->bookingId = $bookingId;
    }

    public function getBookingId()
    {
        return $this->bookingId;
    }

    public function getEventName()
    {
        return 'booking.confirmed';
    }
}

class Booking implements AggregateRoot
{
    use TracksChanges;

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function confirm()
    {
        $this->recordChange(new BookingConfirmed($this->id));
    }
}

class SymfonySendConfirmationListener
{
    public function onBookingConfirmed(BookingConfirmed $event)
    {
        echo sprintf('[Symfony] booking#%s has been confirmed. We need to send a confirmation email.'.PHP_EOL, $event->getBookingId());
    }
}

$eventDispatcher = new EventDispatcher;
$eventDispatcher->addListener('booking.confirmed', [new SymfonySendConfirmationListener, 'onBookingConfirmed']);

$redis = new Redis();
$redis->connect('127.0.0.1');
$changeTracker = new ChangeTracker(
    new LoggerEventBus(
        new EchoLogger,
        new CompositeEventBus([
            new SymfonyEventBus($eventDispatcher),
            new RedisEventBus($redis, 'booking_engine')
        ])
    )
);
$booking = new Booking('1234');
$booking->confirm();

$changeTracker->track($booking);

echo 'Run php examples/redis-worker.php to catch event in an asynchronous way'.PHP_EOL;
