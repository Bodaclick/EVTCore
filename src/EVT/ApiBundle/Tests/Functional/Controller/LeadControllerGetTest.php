<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * LeadControllerGetTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class LeadControllerGetTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    protected $header;

    /**
     * Create a client to test request
     */
    public function setUp()
    {
        $classes = [
            'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadLeadData',
        ];
        $this->loadFixtures($classes);
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];

    }

    public function testGetLeads()
    {
        $this->client->request(
            'GET',
            '/api/leads?apikey=apikeyValue&canView=usernameManager',
            [],
            [],
            [$this->header]
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $arrayLeads = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('items', $arrayLeads);
        $this->assertArrayHasKey('pagination', $arrayLeads);
        $this->assertCount(1, $arrayLeads['items']);
        $this->assertEquals('valid@email.com', $arrayLeads['items'][0]['email']['email']);
        $this->assertEquals('2014-02-20CET23:50:26+0100', $arrayLeads['items'][0]['event']['date']);
        $this->assertEquals('2013-10-10CEST00:00:00+0200', $arrayLeads['items'][0]['created_at']);
        $this->assertEquals('2013-10-12CEST00:00:00+0200', $arrayLeads['items'][0]['read_at']);
    }

    public function testGetLeadsCannotView()
    {
        $this->client->request(
            'GET',
            '/api/leads?apikey=apikeyValue&canView=usernameManagerCannot',
            [],
            [],
            [$this->header]
        );

        $this->assertEquals(Codes::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testGetLeadsNotCanView()
    {
        $this->client->request(
            'GET',
            '/api/leads?apikey=apikeyValue',
            [],
            [],
            [$this->header]
        );

        $this->assertEquals(Codes::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }
}
