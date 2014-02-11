<?php

namespace EVT\CoreDomainBundle\Events;

use EVT\CoreDomain\Provider\Showroom;
use BDK\AsyncEventDispatcher\AsyncEventInterface;

class ShowroomEvent implements AsyncEventInterface
{
    protected $showroom;
    protected $name;

    /**
     * @param Showroom $showroom
     * @param string $name
     */
    public function __construct(Showroom $showroom, $name)
    {
        $this->showroom = $showroom;
        $this->name = $name;
    }

    /**
     * @return Showroom
     */
    public function getShowroom()
    {
        return $this->showroom;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
