<?php

namespace EVT\CoreDomain\Lead;

/**
 * EventType
 *
 * @author    Mario Cazorla  <mcazorla@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class EventType
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
