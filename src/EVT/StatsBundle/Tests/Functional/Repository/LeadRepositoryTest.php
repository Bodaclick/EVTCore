<?php

namespace EVT\StatsBundle\Tests\Functional\Repository;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class LeadRepositoryTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class LeadRepositoryTest extends WebTestCase
{
    private $repo;

    public function setUp()
    {
        $classes = [
            'EVT\StatsBundle\Tests\DataFixtures\ORM\LoadLeadsStatsData',
        ];
        $this->loadFixtures($classes, 'stats');
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->repo = static::$kernel->getContainer()->get('doctrine.orm.stats_entity_manager')
            ->getRepository('EVTStatsBundle:Lead');
    }

    public function testGetTotalForYear()
    {
        $result = $this->repo->getTotalForYear('2014');

        $this->assertEquals(4, $result);
    }

    public function testAddIncrement()
    {
        $originLeads = $this->repo->findBetweenDates('2013-01-01', '2013-01-02');

        $this->repo->add('2013-01-01', 'Europe/Madrid', 'verticalTest1.com', 1, 1);

        $finalLeads = $this->repo->findBetweenDates('2013-01-01', '2013-01-02');

        $this->assertEquals(count($originLeads), count($finalLeads));
        $this->assertEquals(2, $finalLeads[0]->getNumber());
    }

    public function testAddInsert()
    {
        $originLeads = $this->repo->findBetweenDates('2013-04-01', '2013-05-02');

        $this->repo->add('2013-05-01', 'Europe/Madrid', 'verticalTest1.com', 1, 1);

        $finalLeads = $this->repo->findBetweenDates('2013-04-01', '2013-05-02');

        $this->assertEquals(count($originLeads) + 1, count($finalLeads));
        $this->assertEquals(1, $finalLeads[0]->getNumber());
    }
}
