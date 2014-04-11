<?php

namespace EVT\StatsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PostLeadControllerTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class PostLeadControllerTest extends WebTestCase
{
    private $client;
    private $header;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['HTTP_Content-Type' => 'application/json', 'HTTP_Accept'=> 'application/json'];
    }

    public function testReceiveHook()
    {
        $params = [
            'event' => [
                'date' => '2015-12-31T00:00:00+0000',
                'type' => ['type' => 1, 'name' => 'BIRTHDAY'],
                'location' => [
                    "lat"=> 10,
                    "long" => 10,
                    "admin_level1" => "Getafe",
                    "admin_level2" => "Madrid",
                    "country" => "Spain"
                ]
            ],
            'personal_info' => [
                'name' => 'testUserName',
                'surname' => 'testUserSurname',
                'email' => 'valid@email.com',
                'phone' => '+34 0123456789'
            ],
            'showroom' => [
                "slug"=> "name",
                "score"=> 0,
                "name" => 'name',
                "phone" => '1234-call-me',
                "provider"=> [
                    "id"=> "1",
                    "name"=> "name1",
                    "slug"=> "name1",
                    "notification_emails"=> [],
                    "managers"=> [],
                    "location"=> [
                        "lat"=> 10,
                        "long"=> 10,
                        "admin_level1"=> "test",
                        "admin_level2"=> "test",
                        "country"=> "Spain"
                    ]
                ],
                "vertical"=> [
                    "domain"=> "test.com",
                    "lang"=> "es_ES",
                    "timezone"=> "Europe/Madrid",
                    "showrooms"=> []
                ],
                "information_bag"=> [
                    "parameters"=> []
                ],
                "id"=> 1
            ],
            "information_bag"=> [
                "parameters"=> []
            ],
            "created_at"=> "2014-02-12T11:19:29+0000",
            "email"=> [
                "email"=> "valid@email.com"
            ],
            "id"=> "1"
        ];

        $leadRepo = $this->getMockBuilder('\EVT\StatsBundle\Entity\Repository\LeadRepository')
            ->disableOriginalConstructor()->getMock();
        $leadRepo->expects($this->once())
            ->method('add')
            ->with(
                $this->stringContains('2014-02-12'),
                $this->stringContains('Madrid'),
                $this->stringContains('test.com'),
                $this->stringContains('1'),
                $this->equalTo(1)
            );

        $statsEM = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()->getMock();
        $statsEM->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($leadRepo));

        $this->client->getContainer()->set('doctrine.orm.stats_entity_manager', $statsEM);

        $this->client->request(
            'POST',
            '/stats/leads?apikey=apikeyValue',
            [],
            [],
            $this->header,
            json_encode($params)
        );

        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }
}
