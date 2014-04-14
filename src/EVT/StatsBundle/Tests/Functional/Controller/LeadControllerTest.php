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
        $classesApi = [
            'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadProviderData',
        ];
        $this->loadFixtures($classesApi);
        $classesStats = [
            'EVT\StatsBundle\Tests\DataFixtures\ORM\LoadLeadsStatsData',
        ];
        $this->loadFixtures($classesStats, 'stats');

        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];
        $this->repo = static::$kernel->getContainer()->get('doctrine.orm.stats_entity_manager')
            ->getRepository('EVTStatsBundle:Lead');
    }

    public function testGetLeadsByManagerWithProviderAndLeads()
    {
        $this->client->request(
            'GET',
            '/stats/leads?apikey=apikeyValue&from_date=2013-01-01&to_date=2020-12-31&canView=usernameManager',
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

    public function testGetLeadsByManagerWithoutProvider()
    {
        $this->client->request(
            'GET',
            '/stats/leads?apikey=apikeyValue&from_date=2013-01-01&to_date=2020-12-31&canView=usernameManager2',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $arrayLeads = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(null, $arrayLeads);
    }

    public function testGetLeadsByManagerWithProviderWithoutLeads()
    {
        $this->client->request(
            'GET',
            '/stats/leads?apikey=apikeyValue&from_date=2013-01-01&to_date=2020-12-31&canView=usernameManager3',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $arrayLeads = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(null, $arrayLeads);
    }

    public function testGetLeadsByEmployee()
    {
        $this->client->request(
            'GET',
            '/stats/leads?apikey=apikeyValue&from_date=2013-01-01&to_date=2020-12-31&canView=usernameEmployee',
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

        $this->assertCount(6, $arrayLeads);
        $this->assertEquals('verticalTest1.com', $arrayLeads[0]['vertical']);
        $this->assertEquals('verticalTest2.com', $arrayLeads[3]['vertical']);
        $this->assertEquals(1, $arrayLeads[0]['number']);
    }
}
