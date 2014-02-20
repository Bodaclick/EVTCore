<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

class UserControllerTest extends WebTestCase
{
    protected $client;
    protected $header;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'HTTP_ACCEPT' => 'application/json'];
        $this->loadFixtures(
            [
                'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadUserData'
            ]
        );
    }

    public function testGetUser()
    {
        $username = 'usernameManager';

        $this->client->request(
            'GET',
            '/api/users/' . $username . '?apikey=apikeyValue',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(json_decode($this->client->getResponse()->getContent(), true)['username'], $username);
    }
}
