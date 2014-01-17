<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mcazorla
 * Date: 17/01/14
 * Time: 13:10
 * To change this template use File | Settings | File Templates.
 */

namespace EVTCore\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutingControllerTest extends WebTestCase
{
    public function testHttps()
    {
        $client = static::createClient(array(),array('HTTPS' => true));
        $crawler = $client->request('GET', '/EVT/ApiBundle/Controller');
        $this->assertGreaterThan(0, $crawler->filter('que pongo aqui?')->count());
    }
    public function testHttp()
    {
        $client = static::createClient(array(),array('HTTPS' => false));
        $crawler = $client->request('GET', '/EVT/ApiBundle/Controller');
        $this->assertEquals(0, $crawler->filter('que pongo auqi?')->count());
    }
}