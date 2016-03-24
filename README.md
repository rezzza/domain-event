Library to help our apps to be domain event friendly without using EventStore at first.

[![Build Status](https://travis-ci.com/rezzza/domain-event.svg?token=bs6eRqVZF8vUF7BaW6xL&branch=master)](https://travis-ci.com/rezzza/domain-event)

# Example

[See detailled quickstart](examples/quickstart.php)

To run example
```
php examples/redis-worker.php
php examples/quickstart.php
```

In a fullstack way the best option is to track change in your repository

```php
class ORMBookingRepository extends ORMAggregateRootRepository implements BookingRepository
{
    public function find($bookingId)
    {
        $this->getInternalRepository->find($bookingId)
    }

    public function save(Booking $booking)
    {
        $this->getManager()->persist($booking);
        $this->getManager()->flush();
        $this->track($booking);
    }
}

$repository = new ORMBookingRepository(
    new ManagerRegistry,
    'My\FQCN\Booking',
    new ChangeTracker(
        new LoggerEventBus(
            $logger,
            new CompositeEventBus([
                new SymfonyEventBus($eventDispatcher),
                new RedisEventBus($redis, 'booking')
            ])
        )
    )
);
```

# EventDispatcher debug
To debug your own event dispatcher with Symfony, we add a CLI for you. You should register it as a service and use the `--service-id` option.
