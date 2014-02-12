<?php

namespace EVT\CoreDomainBundle\Events;

use EVT\CoreDomain\Provider\Vertical;
use EVT\CoreDomain\User\User;
use BDK\AsyncEventDispatcher\AsyncEventInterface;

class UserEvent implements AsyncEventInterface
{
    protected $user;
    protected $vertical;
    protected $name;

    /**
     * @param Showroom $showroom
     * @param string $name
     */
    public function __construct(User $user, Vertical $vertical, $name)
    {
        $this->user = $user;
        $this->vertical = $vertical;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
