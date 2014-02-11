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
class AddUrlToHookCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
        ->setName('evt:hooks:add')
        ->setDescription('Add a url to a event hook.')
        ->setDefinition(
            [
                new InputArgument('event', InputArgument::REQUIRED, 'The event'),
                new InputArgument('url', InputArgument::REQUIRED, 'The url'),
            ]
        )
        ->setHelp(
            <<<EOT
The <info>evt:hooks:add</info> insert a new url for the event hook:

  <info>php app/console evt:hooks:add user_created http://my.api.com/new_user?apikey=asdf&post=y</info>

to get a events list use <info>php app/console evt:hooks:list </info>

EOT
        );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $event = $input->getArgument('event');
        $url = $input->getArgument('url');

        $asyncService = $this->getContainer()->get('bdk.async_event_dispatcher');
        $events = $asyncService->getEvents();

        if (!isset($events[$event])) {
            $output->writeln(sprintf(
                'Fail: event: <comment>%s</comment> do not exists. Try <info>php app/console evt:hooks:list </info>',
                $event
            ));
            return;
        }

        $hook = new Hook($event, $url);
        $hooksRepo = $this->getContainer()->get('doctrine.orm.default_entity_manager')
            ->getRepository('EVTEvtApplication:Hook');
        $hooksRepo->save($hook);

        $output->writeln(sprintf(
            'Url: <comment>%s</comment> added to the event: <comment>%s</comment>',
            urldecode($url),
            $event
        ));
    }
}
