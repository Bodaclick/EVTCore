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
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'];

    }

    public function mockContainer()
    {        
        $user = $this->getMockBuilder('EVT\CoreDomainBundle\Entity\GenericUser')
            ->disableOriginalConstructor()->getMock();
        
        $userRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\UserRepository')
            ->disableOriginalConstructor()->getMock();
        $userRepo->expects($this->once())
            ->method('find')
            ->will(
                $this->returnValue($user)
            );
        
        $providerRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\ProviderRepository')
            ->disableOriginalConstructor()->getMock();
        
        $providerRepo->expects($this->once())
            ->method('save')
            ->will(
                $this->returnValue(true)
            );

        $this->client->getContainer()->set('evt.repository.user', $userRepo);
        $this->client->getContainer()->set('evt.repository.provider', $providerRepo);
    }

    public function testCreateLead()
    {
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
                'locationLong' => 10
            ]
        ];
        
        $this->mockContainer();
        $this->client->request(
            'POST',
            '/api/providers?apikey=apikeyValue',
            $params,
            [],
            ['Content-Type' => 'application/json', 'Accept' => 'application/json']
        );

        $this->assertEquals(Codes::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testNewLead()
    {
        $crawler = $this->client->request(
            'GET',
            '/api/providers/new?apikey=apikeyValue',
            [],
            [],
            ['Content-Type' => 'text/html', 'Accept' => 'text/html']
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('form'));
    }
}
