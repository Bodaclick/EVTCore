<?php

namespace EVT\CoreDomain\Tests\User;

use EVT\CoreDomain\User\User;

/**
 * GenericUserTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class GenericUserTest extends \PHPUnit_Framework_TestCase
{
    public function testUserCreation()
    {
        $user = new User('name', 'email@mail.com');

        $this->assertEquals('name', $user->getName());
        $this->assertEquals('email@mail.com', $user->getEmail());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidEmail()
    {
        $user = new User('name', 'email.invalid@');
    }
}
