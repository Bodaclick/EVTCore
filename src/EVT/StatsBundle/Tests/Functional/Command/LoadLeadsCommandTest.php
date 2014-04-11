<?php

namespace EVT\StatsBundle\Tests\Functional\Command;

use EVT\StatsBundle\Command\LoadLeadsCommand;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class LoadLeadsCommandTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class LoadLeadsCommandTest extends WebTestCase
{
    protected $application;

    protected $container;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->application = new Application($kernel);
        $this->container = $kernel->getContainer();

        // Create the empty db structure
        $this->loadFixtures([
            'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadLeadData',
        ]);

        $this->loadFixtures([], 'stats');

        $this->application->add(new LoadLeadsCommand());
    }

    protected function executeCommand($commandName, $options = array())
    {
        $command = $this->application->find($commandName);
        $commandTester = new CommandTester($command);
        $defaultOptions = ['command' => $command->getName()];

        $options = array_merge($defaultOptions, $options);

        $commandTester->execute($options);

        return $commandTester;
    }

    public function testCommand()
    {
        $this->executeCommand('evt:stats:load:leads', []);

        $leadRepo = $this->container->get('doctrine.orm.stats_entity_manager')
            ->getRepository('EVTStatsBundle:Lead');
        $finalLeads = $leadRepo->findBetweenDates('2013-10-01', '2013-12-31');
        $this->assertEquals(2, count($finalLeads));
        $this->assertEquals(1, $finalLeads[0]->getNumber());
        $this->assertEquals(1, $finalLeads[1]->getNumber());
    }
}
