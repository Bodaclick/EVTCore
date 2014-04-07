<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * ManagerControllerGetTest
 *
 * @author    Alvaro Prudencio <aprudencio@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class ManagerControllerGetTest extends WebTestCase
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
            'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadUserData',
        ];
        $this->loadFixtures($classes);
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];

    }

    public function testGetManagers()
    {
        $this->client->request(
            'GET',
            '/api/managers?apikey=apikeyValue&canView=usernameEmployee',
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

        $arrayManagers = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('items', $arrayManagers);
        $this->assertArrayHasKey('pagination', $arrayManagers);
        $this->assertCount(2, $arrayManagers['items']);
        $this->assertEquals('valid@emailManager.com', $arrayManagers['items'][0]['user']['email']);
        $this->assertEquals('10', $arrayManagers['pagination']['items_per_page']);
        $this->assertEquals('2', $arrayManagers['pagination']['total_items']);
    }

    public function testGetLeadsCannotView()
    {
        $this->client->request(
            'GET',
            '/api/managers?apikey=apikeyValue&canView=usernameEmployeeCannot',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testGetLeadsNotCanView()
    {
        $this->client->request(
            'GET',
            '/api/managers?apikey=apikeyValue',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }
}
