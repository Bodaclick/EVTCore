<?php

namespace EVT\CoreDomain\Tests\Lead;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testEvent()
    {
        $event = new Event(new EventType('birthday'), new Location(['lat' => 10, 'long' => 10]), new \DateTime('now'));
        $this->assertEquals('birthday', $event->getEventType()->getType());
        $this->assertEquals(['lat' => 10, 'long' => 10], $event->getLocation()->getLatLong());
    }

    public function testEventDateNotChanges()
    {
        $date = new \DateTime('now');
        $event = new Event(new EventType('birthday'), new Location(), $date);
        $date->modify('+1 day');
        $this->assertNotEquals($date, $event->getDate());
    }
}
