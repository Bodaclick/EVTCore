<?php

namespace EVT\CoreDomain\Tests\Lead;

use EVT\CoreDomain\Lead\EventType;

class EventTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     *  @expectedException InvalidArgumentException
     */
    public function testInvalidType()
    {
        new EventType('birthday');
    }
}
