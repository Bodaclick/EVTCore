<?php

namespace EVT\StatsBundle\Tests\Model;

use EVT\StatsBundle\Model\DateShifter;

/**
 * Class DateShifterTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class DateShifterTest extends \PHPUnit_Framework_TestCase
{
    public function testDateShiftSameDay()
    {
        $date = DateShifter::dateShift(
            new \DateTime('2014-02-20 00:00:01', new \DateTimeZone('UTC')),
            'Europe/Madrid'
        );

        $this->assertEquals('2014-02-20', $date);
    }

    public function testDateShiftDayBefore()
    {
        $date = DateShifter::dateShift(
            new \DateTime('2014-02-20 00:00:01', new \DateTimeZone('UTC')),
            'America/New_York'
        );

        $this->assertEquals('2014-02-19', $date);
    }

    /**
     * @expectedException Exception
     */
    public function testDateShiftNoTimezone()
    {
        $date = DateShifter::dateShift(
            new \DateTime('2014-02-20 00:00:01', new \DateTimeZone('UTC')),
            'NoExisting/Timezone'
        );
    }
}