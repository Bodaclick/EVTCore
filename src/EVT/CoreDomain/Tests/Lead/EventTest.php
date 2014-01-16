<?php

namespace EVT\CoreDomain\Tests\Lead;

use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Location;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testEvent()
    {
        $event = new Event(
            new EventType('birthday'),
            new Location(10, 10, 'Madrid', 'Madrid', 'Spain'),
            new \DateTime('now')
        );
        $this->assertEquals('birthday', $event->getEventType()->getType());
        $this->assertEquals(['lat' => 10, 'long' => 10], $event->getLocation()->getLatLong());
    }

    public function testEventDateNotChanges()
    {
        $date = new \DateTime('now');
        $event = new Event(new EventType('birthday'), new Location(10, 10, 'Madrid', 'Madrid', 'Spain'), $date);
        $date->modify('+1 day');
        $this->assertNotEquals($date, $event->getDate());
    }
}
