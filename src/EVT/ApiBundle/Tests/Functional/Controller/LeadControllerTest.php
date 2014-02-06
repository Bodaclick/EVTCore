<?php

namespace EVT\ApiBundle\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * LeadControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 * @group Functional
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
        $this->header = ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];
        $this->loadFixtures(
            ['EVT\ApiBundle\Tests\DataFixtures\ORM\LoadShowroomData']
        );

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

        $this->client->request(
            'POST',
            '/api/leads?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );
        $this->assertEquals(Codes::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $id = explode('?', explode('/', json_decode($this->client->getResponse()->getContent(), true)['lead'])[3])[0];
        $this->assertEquals(1, $id);
    }

    public function testUserCreatedOnLead()
    {
        $params = [
            'lead' => [
                'user' => [
                    'name' => 'testUserName',
                    'surname' => 'testUserSurname',
                    'email' => 'uniqueTest@email.com',
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

        $this->client->request(
            'POST',
            '/api/leads?apikey=apikeyValue',
            $params,
            [],
            ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json']
        );

        $this->assertEquals(Codes::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $eUser = $this->getContainer()->get('doctrine.orm.default_entity_manager')
            ->getRepository('EVTCoreDomainBundle:GenericUser')
            ->findOneByEmail('uniqueTest@email.com');
        $this->assertNotNull($eUser);
    }
}
