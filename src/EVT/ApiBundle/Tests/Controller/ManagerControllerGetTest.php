<?php

namespace EVT\ApiBundle\Tests\Controller;

use EVT\CoreDomain\User\User;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomainBundle\Model\Paginator;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * ManagerControllerGetTest
 *
 * @author    Alvaro Prudencio <aprudencio@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class ManagerControllerGetTest extends WebTestCase
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
    }

    public function mockDataManager()
    {

        $user1 = new User(
            'email1@email.com',
            new PersonalInformation('name', 'b', 'c'),
            null,
            null,
            'ROLE_MANAGER',
            'demo1'
        );
        $rflUser1 = new \ReflectionClass($user1);
        $rflId = $rflUser1->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($user1, 1);

        $user2 = new User(
            'email2@email.com',
            new PersonalInformation('name', 'b', 'c'),
            null,
            null,
            'ROLE_MANAGER',
            'demo2'
        );
        $rflUser2 = new \ReflectionClass($user2);
        $rflId = $rflUser2->getProperty('id');
        $rflId->setAccessible(true);
        $rflId->setValue($user2, 2);

        $userItems[] = $user1;
        $userItems[] = $user2;

        return $userItems;
    }

    public function mockData ()
    {
        $users = $this->mockDataManager();

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
            ->will($this->returnvalue(count($users)));

        return new Paginator($mockPagination, $users);
    }

    public function mockManagersContainer($method, $users)
    {
        $userRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\UserRepository')
            ->disableOriginalConstructor()->getMock();
        $userRepo->expects($this->once())
            ->method($method)
            ->will($this->returnvalue($users));

        $this->client->getContainer()->set('evt.repository.user', $userRepo);
    }

    public function testGetManagers()
    {
        $this->mockManagersContainer('getManagers', $this->mockData());
        $this->client->request(
            'GET',
            '/api/managers?apikey=apikeyValue',
            [],
            [],
            ['Content-Type' => 'application/json', 'HTTP_ACCEPT' => 'application/json']
        );

        $this->assertEquals(Codes::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $arrayManagers = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('items', $arrayManagers);
        $this->assertArrayHasKey('pagination', $arrayManagers);
        $this->assertCount(1, ['items']);
        $this->assertEquals('email1@email.com', $arrayManagers['items'][0]['email']['email']);
        $this->assertEquals(1, $arrayManagers['pagination']['total_pages']);
        $this->assertEquals(1, $arrayManagers['pagination']['current_page']);
        $this->assertEquals(10, $arrayManagers['pagination']['items_per_page']);
        $this->assertEquals(2, $arrayManagers['pagination']['total_items']);
    }
}
