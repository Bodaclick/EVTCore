<?php

namespace EVT\CoreDomainBundle\Events;

use BDK\AsyncEventDispatcher\AsyncEventInterface;
use EVT\CoreDomainBundle\Entity\GenericUser;

/**
 * EmployeeEvent
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class GenericUserEvent implements AsyncEventInterface
{
    protected $user;
    protected $name;

    public function __construct(GenericUser $user, $name)
    {
        $this->user = $user;
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
