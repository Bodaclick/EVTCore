<?php


namespace EVT\ApiBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

class LeadControllerErrorTest extends WebTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];
        $this->loadFixtures(
            ['EVT\ApiBundle\Tests\DataFixtures\ORM\LoadShowroomData']
        );
    }

    public function badDataProvider()
    {

        return [
            [
                'lead' => [
                    'user' => [
                        'name' => 'testUserName',
                        'surname' => 'testUserSurname',
                        'email' => 'valid@email.com',
                        'phone' => '+34 0123456789'
                    ],
                    'event' => [
                        'date' => '2015/12-',
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
            ],
            [
                'lead' => [
                    'user' => [
                        'name' => 'testUserName',
                        'surname' => 'testUserSurname',
                        'email' => 'valid@email.com',
                        'phone' => '+34 0123456789'
                    ],
                    'event' => [
                        'date' => '2015/12/15',
                        'type' => 1,
                        'location' => [
                            'lat' => null,
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
            ],
            [
                'lead' => [
                    'user' => [
                        'name' => 'testUserName',
                        'surname' => 'testUserSurname',
                        'email' => 'invalidemail.com',
                        'phone' => '+34 0123456789'
                    ],
                    'event' => [
                        'date' => '2015/12/15',
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
            ],
            [
                'lead' => [
                    'user' => [
                        'name' => 'testUserName',
                        'surname' => 'testUserSurname',
                        'email' => 'valid@email.com',
                        'phone' => '+34 0123456789'
                    ],
                    'event' => [
                        'date' => '2015/12/15',
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
                        'id' => 20
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider badDataProvider
     */
    public function testErrorHandling($params)
    {
        $this->client->request(
            'POST',
            '/api/leads?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}
 