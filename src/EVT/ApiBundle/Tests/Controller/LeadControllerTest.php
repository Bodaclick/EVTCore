<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * LeadControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class LeadControllerTest extends WebTestCase
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
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo = $this->getMockBuilder('EVT\CoreDomain\Provider\ShowroomRepositoryInterface')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo->expects($this->once())
            ->method('findShowroom')
            ->will(
                $this->returnValue($showroom)
            );

        $leadRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\LeadRepository')
            ->disableOriginalConstructor()->getMock();
        $leadRepo->expects($this->once())
            ->method('save')
            ->will(
                $this->returnvalue(true)
            );

        $userRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\UserRepository')
            ->disableOriginalConstructor()->getMock();
        $userRepo->expects($this->once())
            ->method('save')
            ->will(
                $this->returnvalue(true)
            );

        $this->client->getContainer()->set('evt.repository.showroom', $showroomRepo);
        $this->client->getContainer()->set('evt.repository.lead', $leadRepo);
        $this->client->getContainer()->set('evt.repository.user', $userRepo);
    }

    public function testCreateLead()
    {
        $params = [
            'lead' => [
                'user' => [
                    'name' => 'testUserName',
                    'surname' => 'testUserSurname',
                    'email' => 'valid@email.com',
                    'phone' => '+34 0123456789'
                ],
                'event' => [
                    'date' => '2015/12/31',
                    'type' => 1,
                    'location' => [
                       'lat' => 10,
                       'long' => 10,
                       'admin_level_1' => 'Getafe',
                       'admin_level_2' => 'Madrid',
                       'country' => 'Spain'
                    ]
                ],
                'showroom' => [
                    'id' => '1'
                ]
            ]
        ];

        $this->mockContainer();
        $this->client->request(
            'POST',
            '/api/leads?apikey=apikeyValue',
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
            '/api/leads/new?apikey=apikeyValue',
            [],
            [],
            ['Content-Type' => 'text/html', 'Accept' => 'text/html']
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('button'));
    }
}
