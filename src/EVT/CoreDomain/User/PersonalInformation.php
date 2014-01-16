<?php

namespace EVT\CoreDomain\User;

use \IteratorAggregate;
use \ArrayIterator;

/**
 * PersonalInformation
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class PersonalInformation implements IteratorAggregate
{
    public $name;
    public $surnames;
    public $phone;

    /**
     * __construct
     *
     * @param mixed $name
     * @param mixed $surnames
     * @param mixed $phone
     */
    public function __construct($name = '', $surnames = '', $phone = '')
    {
        $this->name     = $name;
        $this->surnames = $surnames;
        $this->phone    = $phone;
    }

    /**
     * getIterator
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this);
    }
}
