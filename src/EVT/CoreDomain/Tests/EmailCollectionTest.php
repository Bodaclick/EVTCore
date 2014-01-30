<?php

namespace EVT\CoreDomain\Tests;

use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;

/**
 * EmailCollectionTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class EmailCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $emailCollection = new EmailCollection(new Email('valid@email.com'));
        $this->assertCount(1, $emailCollection);
    }
}
