<?php

namespace EVT\StatsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadLeadsCommand
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class LoadLeadsCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('evt:stats:load:leads')
            ->setDescription('Loads the stats for the leads. Empty the db before execute!')
            ->setHelp(
                <<<EOT
Loads the stats for the leads. Empty the db before execute!
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $leadsRepo = $this->getContainer()->get('evt.repository.lead');
        $statsLeadsRepo = $this->getContainer()->get('doctrine.orm.stats_entity_manager')
            ->getRepository('EVTStatsBundle:Lead');
        $leads = $leadsRepo->findAll();

        foreach ($leads as $lead) {
            $statsLeadsRepo->add(
                $lead->getEvent()->getDate()->format('Y-m-d'),
                'Europe/Madrid', //$lead->getShowroom()->getVertical()->getTimezone(),
                $lead->getShowroom()->getVertical()->getDomain(),
                $lead->getShowroom()->getProvider()->getId(),
                $lead->getShowroom()->getId()
            );
            $output->writeln(sprintf(
                'Add leadId: <comment>%s</comment> CORRECT THE TIMEZONE, get it from vertical!!',
                $lead->getId()
            ));
        }
    }
}
