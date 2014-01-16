<?php

namespace EVT\CoreDomain\Lead;

/**
 * Event
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class Event
{
    private $date;
    private $type;
    private $location;

    public function __construct(EventType $type,Location $location,\DateTime $date)
    {
        $this->type = $type;
        $this->location = $location;
        $this->date = clone $date;

    }
    public function getEventType()
    {
        return $this->type;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function getDate()
    {
        return $this->date;
    }
}
