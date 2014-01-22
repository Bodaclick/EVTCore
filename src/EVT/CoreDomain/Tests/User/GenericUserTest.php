<?php

namespace EVT\CoreDomain\Tests\User;

use EVT\CoreDomain\User\User;
use EVT\CoreDomain\User\PersonalInformation;

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
        $user = new User('email@mail.com', new PersonalInformation('name', 'b', 'c'));
        $this->assertEquals('name', $user->getPersonalInformation()->name);
        $this->assertEquals('email@mail.com', $user->getEmail());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidEmail()
    {
        $user = new User('email.invalid@', new PersonalInformation('a', 'b', 'c'));
    }
}
