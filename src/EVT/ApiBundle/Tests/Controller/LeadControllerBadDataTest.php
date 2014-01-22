<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

class LeadControllerBadDataTest extends WebTestCase
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

        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo = $this->getMockBuilder('EVT\CoreDomain\Provider\ShowroomRepositoryInterface')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo->expects($this->once())
            ->method('find')
            ->will(
                $this->returnValue($showroom)
            );

        $this->client->getContainer()->set('evt.repository.showroom', $showroomRepo);
    }

    public function provider()
    {
        return [
            [
                '"date not found"',
                ['lead' => [
                    'user' => [
                        'name' => 'noDate',
                        'surname' => 'testUserSurname',
                        'email' => 'valid@email.com',
                        'phone' => '+34 0123456789'
                    ],
                    'event' => [
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
                ]]
            ],
            [
                '"type not found"',
                ['lead' => [
                    'user' => [
                        'name' => 'noType',
                        'surname' => 'testUserSurname',
                        'email' => 'valid@email.com',
                        'phone' => '+34 0123456789'
                    ],
                    'event' => [
                        'date' => '2015/12/31',
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
                ]]
            ],
            [
                '"location not found"',
                ['lead' => [
                    'user' => [
                        'name' => 'noLocation',
                        'surname' => 'testUserSurname',
                        'email' => 'valid@email.com',
                        'phone' => '+34 0123456789'
                    ],
                    'event' => [
                        'date' => '2015/12/31',
                        'type' => '1'
                    ],
                    'showroom' => [
                        'id' => '1'
                    ]
                ]]
            ],
            [
                '"lat not found"',
                ['lead' => [
                    'user' => [
                        'name' => 'noLat',
                        'surname' => 'testUserSurname',
                        'email' => 'valid@email.com',
                        'phone' => '+34 0123456789'
                    ],
                    'event' => [
                        'date' => '2015/12/31',
                        'type' => '1',
                        'location' => [
                            'long' => 10,
                            'admin_level_1' => 'Getafe',
                            'admin_level_2' => 'Madrid',
                            'country' => 'Spain'
                        ]
                    ],
                    'showroom' => [
                        'id' => '1'
                    ]
                ]]
            ]
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testCrateLeadKO($message, $params)
    {
        $this->client->request(
            'POST',
            '/api/leads?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($message, $this->client->getResponse()->getContent());
    }
}
