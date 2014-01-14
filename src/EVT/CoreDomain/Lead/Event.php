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

    public function __construct(\DateTime $date)
    {
        $this->date = $date;
    }
}
