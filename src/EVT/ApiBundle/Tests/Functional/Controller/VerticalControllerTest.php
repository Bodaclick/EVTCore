<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * VerticalControllerTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class VerticalControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    protected $header;

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];
        $this->loadFixtures(
            ['EVT\ApiBundle\Tests\DataFixtures\ORM\LoadShowroomData']
        );
    }

    public function testVertical()
    {
        $this->client->request(
            'GET',
            '/api/verticals?apikey=apikeyValue&canView=usernameManager',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('test.com', json_decode($this->client->getResponse()->getContent(), true)[0]['domain']);
    }
}
