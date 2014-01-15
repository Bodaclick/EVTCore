<?php

namespace EVT\CoreDomain\Tests;

use EVT\CoreDomain\Email;

/**
 * EmailTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testValidEmail()
    {
        $email = new Email('valid.Email@domain.com');
        $this->assertEquals('valid.Email@domain.com', $email->getEmail());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidEmail()
    {
        $email = new Email('invalid.Email');
    }
}
