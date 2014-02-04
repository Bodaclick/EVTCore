<?php

namespace EVT\CoreDomainBundle\Events;

use EVT\CoreDomain\Provider\Showroom;
use Symfony\Component\EventDispatcher\Event;
use BDK\AsyncDispatcherBundle\Model\EventDispatcher\AsyncEventInterface;

class ShowroomEvent extends Event implements AsyncEventInterface
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
     * @param Showroom $showroom
     */
    public function setShowroom($showroom)
    {
        $this->showroom = $showroom;
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
