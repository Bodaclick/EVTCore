<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

class LeadControllerUserMissingTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    public function testMissingUserData()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'];

        $showroom = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo = $this->getMockBuilder('EVT\CoreDomain\Provider\ShowroomRepositoryInterface')
            ->disableOriginalConstructor()->getMock();

        $showroomRepo->expects($this->never())
            ->method('find')
            ->will(
                $this->returnValue($showroom)
            );

        $this->client->getContainer()->set('evt.repository.showroom', $showroomRepo);

        $params = [
           ['lead' => [
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
        ];
        $header = ['Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'];
        $this->client->request(
            'POST',
            '/api/leads?apikey=apikeyValue',
            $params,
            [],
            $header
        );

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(
            'user not found',
            json_decode($this->client->getResponse()->getContent(), true)[0]['message']
        );
    }
}
