<?php

namespace EVT\StatsBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * Class LeadControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class LeadControllerTest extends WebTestCase
{
    protected $repo;
    protected $client;
    protected $header;

    public function setUp()
    {
        $classes = [
            'EVT\StatsBundle\Tests\DataFixtures\ORM\LoadLeadsStatsData',
        ];
        $this->loadFixtures($classes, 'stats');
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];
        $this->repo = static::$kernel->getContainer()->get('doctrine.orm.stats_entity_manager')
            ->getRepository('EVTStatsBundle:Lead');
    }

    public function testGetLeads()
    {
        $this->client->request(
            'GET',
            '/stats/leads?apikey=apikeyValue&from_date=2013-01-01&to_date=2020-12-31',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $arrayLeads = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(3, $arrayLeads);
        $this->assertEquals('verticalTest1.com', $arrayLeads[0]['vertical']);
        $this->assertEquals(1, $arrayLeads[0]['number']);
    }

    public function testGetNoLeads()
    {
        $this->client->request(
            'GET',
            '/stats/leads?apikey=apikeyValue&from_date=2010-01-01&to_date=2010-01-01',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $arrayLeads = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(0, $arrayLeads);
    }
}
