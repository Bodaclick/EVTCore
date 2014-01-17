<?php

namespace EVT\ApiBundle\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiKeyTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testGoodApikey()
    {
        $content = $this->client->request('GET', '/?apikey=apikeyValue');
        $statusCode = $this->client->getResponse()->getStatusCode();

        $this->assertEquals('200', $statusCode);
    }

    public function testNoApikey()
    {
        $content = $this->client->request('GET', '/');
        $statusCode = $this->client->getResponse()->getStatusCode();
        $content = $this->client->getResponse()->getContent();

        $this->assertEquals('401', $statusCode);
    }

    public function testWrongApikey()
    {
        $content = $this->client->request('GET', '/?apikey=wrongApikeyValue');
        $statusCode = $this->client->getResponse()->getStatusCode();
        $content = $this->client->getResponse()->getContent();

        $this->assertEquals('401', $statusCode);
    }
}
