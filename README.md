Library to help our apps to be domain event friendly without using EventStore at first.

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
        $this->changeTracker->track($voucher);
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
