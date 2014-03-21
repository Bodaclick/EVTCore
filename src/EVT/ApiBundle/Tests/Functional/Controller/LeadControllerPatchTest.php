<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * LeadControllerPatchTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LeadControllerPatchTest extends WebTestCase
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

    public function testReadLead()
    {
        $this->client->request(
            'PATCH',
            '/api/leads/1/read?apikey=apikeyValue&canView=usernameManager',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_ACCEPTED, $this->client->getResponse()->getStatusCode());

        $this->client->request(
            'GET',
            '/api/leads/1?apikey=apikeyValue&canView=usernameManager',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $lead = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('read_at', $lead);
        $this->assertNotEquals('2013-10-12CEST00:00:00+0200', $lead['read_at']);
    }
}
