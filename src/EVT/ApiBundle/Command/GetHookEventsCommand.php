<?php
namespace EVT\ApiBundle\Command;

use EVT\EvtApplication\Entity\Hook;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * AddUrlToHookCommand
 *
 * Add a url to the hooks for a given event
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class GetHookEventsCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
        ->setName('evt:hooks:list')
        ->setDescription('List all the events that can be used for register a hook on.')
        ->setHelp(
            <<<EOT
Get a events list use <info>php app/console evt:hooks:list </info>

EOT
        );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $asyncService = $this->getContainer()->get('bdk.async_event_dispatcher');

        $events = $asyncService->getEvents();
        $eventsTypes = array_keys($events);

        foreach ($eventsTypes as $eventsType) {
            $output->writeln(sprintf('Event: <comment>%s</comment>', $eventsType));
        }
    }
}
