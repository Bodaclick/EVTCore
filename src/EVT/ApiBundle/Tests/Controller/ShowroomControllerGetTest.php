<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * Class ShowroomControllerTest
 * @package EVT\ApiBundle\Tests\Controller
 */
class ShowroomControllerTest extends WebTestCase
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
        $params = ['showroom' => ['vertical' => 'example.com', 'provider' => 1, 'score' => 1 ]];

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

    public function testGetShowrooms()
    {


    }
}
