<?php

namespace EVT\CoreDomainBundle\Test\Functional\Repository;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * UserRepositoryTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class UserRepositoryTest extends WebTestCase
{
    private $repo;

    public function setUp()
    {
        $classes = [
            'EVT\CoreDomainBundle\Tests\DataFixtures\ORM\LoadUserData',
        ];
        $this->loadFixtures($classes);
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->repo = static::$kernel->getContainer()->get('evt.repository.user');
    }

    public function testGetEmployeeByUsername()
    {
        $empl = $this->repo->getEmployeeByUsername('usernameEmployee');
        $this->assertInstanceOf('\EVT\CoreDomain\User\Employee', $empl);
        $this->assertEquals('valid@emailEmployee.com', $empl->getEmail());
    }
}
