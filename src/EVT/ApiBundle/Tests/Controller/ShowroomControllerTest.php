<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * ShowroomControllerTest
 *
 * @author    Quique Torras <etorras@gmail.com>
 * @copyright 2014 Bodaclick S.A
 */
class ShowroomControllerTest extends WebTestCase
{
    protected $client;
    protected $header;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'HTTP_ACCEPT' => 'application/json'];
    }

    public function mockContainer()
    {
        $showroomFactory = $this->getMockBuilder('EVT\ApiBundle\Factory\ShowroomFactory')->disableOriginalConstructor()
            ->getMock();
        $showroomMock = $this->getMockBuilder('EVT\CoreDomain\Provider\Showroom')->disableOriginalConstructor()
            ->getMock();
        $showroomMock->expects($this->once())->method('getId')->will($this->returnValue(1));
        $showroomFactory->expects($this->once())->method('createShowroom')->will($this->returnValue($showroomMock));

        $this->client->getContainer()->set('evt.factory.showroom', $showroomFactory);
    }

    public function testCreate()
    {
        $params = [
            'domain' => 'example.com',
            'providerId' => 1,
            'score' => 1
        ];

        $this->mockContainer();
        $this->client->request(
            'POST',
            '/api/showrooms?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $this->assertRegExp(
            '/\/api\/showrooms\/\d+/',
            json_decode($this->client->getResponse()->getContent(), true)['showroom'],
            $this->client->getResponse()->getContent()
        );
    }
}
