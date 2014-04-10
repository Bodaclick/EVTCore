<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

 /**
 * VerticalControllerTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class VerticalControllerTest extends WebTestCase
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
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'HTTP_Accept' => 'application/json'];
    }

    public function testVertical()
    {
        $vertical = $this->getMockBuilder('EVT\CoreDomain\Provider\Vertical')
            ->disableOriginalConstructor()->getMock();

        $verticalRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\VerticalRepository')
            ->disableOriginalConstructor()->getMock();

        $verticalRepo->expects($this->once())
            ->method('findAllWithCanview')
            ->will(
                $this->returnValue([$vertical])
            );

        $this->client->getContainer()->set('evt.repository.vertical', $verticalRepo);

        $this->client->request(
            'GET',
            '/api/verticals?apikey=apikeyValue&canView=employee',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
