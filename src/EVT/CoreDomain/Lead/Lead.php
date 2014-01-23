<?php

namespace EVT\CoreDomain\Lead;

use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\LeadInformationBag;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\User\User;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\Email;

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
    private $email;
    protected $id;

    /**
     * __construct
     *
     * @param LeadId              $id
     * @param PersonalInformation $user
     * @param Email               $email
     * @param Showroom            $showroom
     * @param Event               $event
     */
    public function __construct(
        LeadId $id,
        PersonalInformation $personalInfo,
        Email $email,
        Showroom $showroom,
        Event $event
    ) {
        $this->id             = $id->getValue();
        $this->showroom       = $showroom;
        $this->personalInfo   = $personalInfo;
        $this->event          = $event;
        $this->createdAt      = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->email          = $email;
        $this->informationBag = new LeadInformationBag();
    }

    public function setInformationBag(LeadInformationBag $informationBag)
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

    /**
     * @return LeadId The id of the lead
     */
    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     *
     * @return Email the email
     */
    public function getEmail()
    {
        return $this->email;
    }
}
