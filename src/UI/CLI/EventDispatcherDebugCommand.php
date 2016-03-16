<?php

namespace Rezzza\DomainEvent\UI\CLI;

use Symfony\Bundle\FrameworkBundle\Command\EventDispatcherDebugCommand as BaseEventDispatcherDebugCommand;
use Symfony\Bundle\FrameworkBundle\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EventDispatcherDebugCommand extends BaseEventDispatcherDebugCommand
{
    protected function configure()
    {
        parent::configure();
        $this->addOption('service-id', null, InputOption::VALUE_REQUIRED, 'The id of the service you want debug', 'event_dispatcher');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);
        $dispatcher = $this->getContainer()->get($input->getOption('service-id'));

        $options = array();
        if ($event = $input->getArgument('event')) {
            if (!$dispatcher->hasListeners($event)) {
                $output->warning(sprintf('The event "%s" does not have any registered listeners.', $event));

                return;
            }

            $options = array('event' => $event);
        }

        $helper = new DescriptorHelper();
        $options['format'] = $input->getOption('format');
        $options['raw_text'] = $input->getOption('raw');
        $options['output'] = $output;
        $helper->describe($output, $dispatcher, $options);
    }
}
