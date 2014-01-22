<?php

namespace EVT\CoreDomain\Tests\Lead;

use EVT\CoreDomain\Lead\Location;

class LocationTest extends \PHPUnit_Framework_TestCase
{
    public function argsProvider()
    {
        return [['', 1, 2, 3, 4], [1, '', 2, 3, 4], [1, 2, '', 3, 4], [1, 2, 3, '', 4], [1, 2, 3, 4, ''] ];
    }

    /**
     *  @expectedException InvalidArgumentException
     *  @dataProvider argsProvider
     */
    public function testLocationIsNotEmpty($arg1, $arg2, $arg3, $arg4, $arg5)
    {
        new Location($arg1, $arg2, $arg3, $arg4, $arg5);
    }
}
