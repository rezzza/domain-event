Library to help our apps to be domain event friendly without using EventStore at first.

[![Build Status](https://travis-ci.com/rezzza/domain-event.svg?token=bs6eRqVZF8vUF7BaW6xL&branch=master)](https://travis-ci.com/rezzza/domain-event)

# Exemple

```php

class Voucher implements AggregateRoot
{
    use TracksChanges;

    public function refund()
    {
        $this->recordChange(VoucherRefunded($this->id));
    }
}

class ORMVoucherRepository extends ORMAggregateRootRepository implements VoucherRepository
{
    public function find($voucherId)
    {
        $this->getInternalRepository->find($voucherId)
    }

    public function save(Voucher $voucher)
    {
        $this->getManager()->persist($voucher);
        $this->getManager()->flush();
        $this->track($voucher);
    }
}

$changeTracker = new ChangeTracker(
    new LoggerEventBus(
        new CompositeEventBus([
            new SymfonyEventBus($eventDispatcher),
            new RedisEventBus($redis, 'voucher')
        ])
    )
);
```

# EventDispatcher debug
To debug your own event dispatcher with Symfony, we add a CLI for you. You should register it as a service and use the `--service-id` option.
