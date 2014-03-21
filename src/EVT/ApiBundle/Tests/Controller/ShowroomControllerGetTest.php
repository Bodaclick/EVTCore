<?php

namespace EVT\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\Provider\Vertical;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\Provider\ShowroomType;
use EVT\CoreDomainBundle\Model\Paginator;

/**
 * Class ShowroomControllerGetTest
 * @package EVT\ApiBundle\Tests\Controller
 */
class ShowroomControllerGetTest extends WebTestCase
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

    public function mockDataShowroom()
    {
        $showroom = new Showroom(
            new Provider(
                new ProviderId(1),
                'providername',
                new EmailCollection(
                    new Email('valid2@email.com')
                )
            ),
            new Vertical('test.com'),
            new ShowroomType(ShowroomType::FREE)
        );

        $rflShowroom = new \ReflectionClass($showroom);
        $rflId = $rflShowroom->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($showroom, 1);

        return $showroom;
    }

    public function mockData()
    {
        $showroom = $this->mockDataShowroom();

        $mockPagination = $this->getMockBuilder('Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination')
            ->disableOriginalConstructor()->getMock();
        $mockPagination->expects($this->once())
            ->method('getCurrentPageNumber')
            ->will($this->returnvalue(1));
        $mockPagination->expects($this->once())
            ->method('getItemNumberPerPage')
            ->will($this->returnvalue(10));
        $mockPagination->expects($this->once())
            ->method('getTotalItemCount')
            ->will($this->returnvalue(1));

        return new Paginator($mockPagination, [$showroom]);
    }

    public function mockContainer($method, $dataShowroom)
    {
        $showroomMock = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\ShowroomRepository')
            ->disableOriginalConstructor()->getMock();
        $showroomMock->expects($this->once())->method($method)->will($this->returnvalue($dataShowroom));

        $this->client->getContainer()->set('evt.repository.showroom', $showroomMock);

    }

    public function testGetShowrooms()
    {
        $this->mockContainer('findByOwner', $this->mockData());
        $this->client->request(
            'GET',
            '/api/showrooms?apikey=apikeyValue',
            [],
            [],
            $this->header
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $arrayShowrooms = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('items', $arrayShowrooms);
        $this->assertArrayHasKey('pagination', $arrayShowrooms);
        $this->assertCount(1, ['items']);
        $this->assertEquals('valid2@email.com', $arrayShowrooms['items'][0]['provider']['notification_emails']);
        $this->assertEquals('FREE', $arrayShowrooms['items'][0]['type']['name']);
        $this->assertEquals(1, $arrayShowrooms['pagination']['total_pages']);
        $this->assertEquals(1, $arrayShowrooms['pagination']['current_page']);
        $this->assertEquals(10, $arrayShowrooms['pagination']['items_per_page']);
        $this->assertEquals(1, $arrayShowrooms['pagination']['total_items']);
    }
}
