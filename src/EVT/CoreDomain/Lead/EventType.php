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
