<?php

namespace EVT\StatsBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EVT\StatsBundle\Entity\Lead;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadLeadsStatsData
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class LoadLeadsStatsData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $years = ['2013-01-01', '2014-01-01', '2014-03-02'];

        foreach ($years as $year) {
            $lead = new Lead(
                new \DateTime($year),
                'verticalTest1.com',
                1,
                1
            );
            $manager->persist($lead);

        }
        foreach ($years as $year) {
            $lead2 = new Lead(
                new \DateTime($year),
                'verticalTest2.com',
                3,
                2
            );
            $manager->persist($lead2);

        }
        $manager->flush();
    }
}
