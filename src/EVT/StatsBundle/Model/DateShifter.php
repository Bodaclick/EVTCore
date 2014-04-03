<?php

namespace EVT\StatsBundle\Model;

/**
 * Class DateShifter
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class DateShifter
{
    /**
     *  Gives the 'Y-m-d' of the $dateTime placed in the given timezone
     *
     * @param \DateTime $dateTime the original dateTime in UTC
     * @param string    $timezone one between: http://www.php.net/manual/en/timezones.php
     *
     * @return string The 'Y-m-d' of the $dateTime placed in the given timezone
     */
    public static function dateShift(\DateTime $dateTime, $timezone)
    {
        $dateTime->setTimezone(new \DateTimeZone($timezone));
        return $dateTime->format('Y-m-d');
    }
}
