<?php

namespace EVT\CoreDomainBundle\Test\Functional\Repository;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * ShowroomRepositoryTest
 *
 * @author    Alvaro Prudencio <aprudencio@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class ShowroomRepositoryTest extends WebTestCase
{
    private $repo;

    public function setUp()
    {
        $classes = [
            'EVT\ApiBundle\Tests\DataFixtures\ORM\LoadShowroomData',
        ];
        $this->loadFixtures($classes);
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->repo = static::$kernel->getContainer()->get('evt.repository.showroom');
    }

    public function testFindByOwner()
    {
        $showroom = $this->repo->findByOwner('usernameManager', 1);

        $this->assertCount(2, $showroom->getItems());
        $this->assertInstanceOf('EVT\CoreDomain\Provider\Showroom', $showroom->getItems()[0]);
        $this->assertEquals('valid@email.com', $showroom->getItems()[0]->getProvider()
                ->getNotificationEmails()[0]->getEmail());
        $this->assertEquals(1, $showroom->getPagination()['total_pages']);
        $this->assertEquals(1, $showroom->getPagination()['current_page']);
        $this->assertEquals(10, $showroom->getPagination()['items_per_page']);
        $this->assertEquals(2, $showroom->getPagination()['total_items']);
    }

    public function testFindByOwnerKO()
    {
        $showroom = $this->repo->findByOwner('usernameManager2');

        $this->assertNull($showroom);
    }
}
