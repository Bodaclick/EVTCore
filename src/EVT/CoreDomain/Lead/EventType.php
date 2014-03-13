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

    const BIRTHDAY = 1;
    const ANNIVERSARY = 2;
    const CHRISTENING = 3;
    const COMMUNION = 4;
    const FAMILY_CELEBRATION = 5;
    const DEBS = 6;
    const XV_YEARS = 7;
    const CORPORATE = 8;
    const GRADUATION = 9;
    const MEMORIAL = 10;
    const RETIREMENT = 11;
    const ENGAGEMENT_PARTY = 12;
    const PARTY = 13;
    const CHRISTMAS = 14;
    const WEDDING = 15;
    const DIVORCE = 16;
    const CATERING = 17;
    const BUFFET = 18;
    const BAR_MITZVAH = 19;
    const GENERIC_SPACE = 20;
    const UNKNOWN = 21;

    private $type;
    private $name;

    public function __construct($type)
    {
        if (!$this->isValidType($type)) {
            throw new \InvalidArgumentException("$type is not a valid type");
        }
        $this->type = $type;
        $this->setName();
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function isValidType($type)
    {
        $rfl = new \ReflectionClass($this);
        if (!array_search($type, $rfl->getConstants())) {
            return false;
        }
        return true;
    }

    private function setName()
    {
        $rfl = new \ReflectionClass($this);
        $this->name = array_search($this->type, $rfl->getConstants());
    }
}
