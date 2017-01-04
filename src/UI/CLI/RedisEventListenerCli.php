<?php

namespace Rezzza\DomainEvent\UI\CLI;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Rezzza\DomainEvent\Infra\Messaging\Redis\RedisEventListener;

class RedisEventListenerCli extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vlr:redis-listener')
            ->addArgument('serviceId', InputArgument::REQUIRED, 'id of RedisEventListener implementation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serviceId = $input->getArgument('serviceId');
        $listener = $this->getContainer()->get($serviceId);

        if ($listener instanceof RedisEventListener) {
            $listener->registerConsumer();
        } else {
            throw new \RuntimeException(sprintf('Service %s should be a %s', $serviceId, RedisEventListener::class));
        }
    }
}
