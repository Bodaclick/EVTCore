<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

class LeadControllerNoShowroomFoundTest extends WebTestCase
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
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded'];
    }

    public function testCretareFailShowroomNotFound()
    {
        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo = $this->getMockBuilder('EVT\CoreDomain\Provider\ShowroomRepositoryInterface')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo->expects($this->once())
            ->method('find')
            ->will(
                $this->returnValue(null)
            );
        $this->client->getContainer()->set('evt.repository.showroom', $showroomRepo);

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
                    'type' => '1',
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

        $this->client->request(
            'POST',
            '/api/leads?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(
            'Showroom not found',
            json_decode($this->client->getResponse()->getContent(), true)[0]['message']
        );
    }
}
