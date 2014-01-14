<?php

namespace EVT\CoreDomain\Lead;

use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\User\User;

/**
 * Lead
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class Lead
{
    private $event;
    private $user;
    private $showroom;
    private $informationBag;
    protected $id;

    /**
     * __construct
     *
     * @param User     $user
     * @param Showroom $showroom
     * @param Event    $event
     */
    public function __construct(LeadId $id, User $user, Showroom $showroom, Event $event)
    {
        $this->id = $id->getValue();
        $this->showroom = $showroom;
        $this->user     = $user;
        $this->event    = $event;
    }
    
    public function setInformationBag($informationBag)
    {
        $this->informationBag = $informationBag;
    }

    public function getInformationBag()
    {
        return $this->informationBag;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getShowroom()
    {
        return $this->showroom;
    }

    public function getId()
    {
        return $this->id;
    }
}
