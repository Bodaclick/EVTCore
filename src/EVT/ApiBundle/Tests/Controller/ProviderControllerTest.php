<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * ProviderControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderControllerTest extends WebTestCase
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
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'HTTP_ACCEPT' => 'application/json'];

    }

    public function testCreateProvider()
    {
        $provider = $this->getMockBuilder('EVT\CoreDomain\Provider\Provider')->disableOriginalConstructor()
            ->getMock();
        $provider->expects($this->once())->method('getId')->will($this->returnValue(1));

        $providerFactory = $this->getMockBuilder('EVT\ApiBundle\Factory\ProviderFactory')
            ->disableOriginalConstructor()->getMock();
        $providerFactory->expects($this->once())->method('createProvider')->will($this->returnValue($provider));

        $this->client->getContainer()->set('evt.factory.provider', $providerFactory);

        $params = [
            'provider' => [
                'genericUser' => [1],
                'name' => 'dgfsdg',
                'phone' => 'asdf',
                'slug' => 'asdf',
                'locationAdminLevel1' => 'asdf',
                'locationAdminLevel2' => 'asdf',
                'locationCountry' => 'asdf',
                'locationLat' => 10,
                'locationLong' => 10,
                'notificationEmails' => 'valid@email.com'
            ]
        ];

        $this->client->request(
            'POST',
            '/api/providers?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('{"provider":"\/api\/providers\/1"}', $this->client->getResponse()->getContent());
    }

    public function testNewProvider()
    {
        $crawler = $this->client->request(
            'GET',
            '/api/providers/new?apikey=apikeyValue',
            [],
            []
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('button'));
    }
}
