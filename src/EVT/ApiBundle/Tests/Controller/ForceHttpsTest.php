<?php

namespace EVTCore\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class RoutingControllerTest extends WebTestCase
{
    public function testHttps()
    {
        $client = static::createClient(array(),array('HTTPS' => true));
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse($crawler)->getStatusCode());
    }
    public function testHttpNotAllowed()
    {
        $client = static::createClient(array(),array('HTTPS' => false));
        $crawler = $client->request('GET', '/');
        $this->assertNotEquals(200, $client->getResponse($crawler)->getStatusCode());
    }
}