<?php

namespace EVT\CoreDomain\Lead;

use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\User\User;
use EVT\CoreDomain\User\PersonalInformation;

/**
 * Lead
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class Lead
{
    private $event;
    private $personalInfo;
    private $showroom;
    private $informationBag;
    private $createdAt;
    protected $id;

    /**
     * __construct
     *
     * @param LeadId              $id
     * @param PersonalInformation $user
     * @param Showroom            $showroom
     * @param Event               $event
     */
    public function __construct(LeadId $id, PersonalInformation $personalInfo, Showroom $showroom, Event $event)
    {
        $this->id           = $id->getValue();
        $this->showroom     = $showroom;
        $this->personalInfo = $personalInfo;
        $this->event        = $event;
        $this->createdAt    = new \DateTime('now', new \DateTimeZone('UTC'));
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

    public function getPersonalInformation()
    {
        return $this->personalInfo;
    }

    public function getShowroom()
    {
        return $this->showroom;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
