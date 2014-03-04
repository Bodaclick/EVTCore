<?php

namespace EVT\CoreDomainBundle\Entity;

use EVT\CoreDomainBundle\Entity\Showroom;
use EVT\CoreDomainBundle\Model\LeadInformation;

/**
 * Lead
 */
class Lead
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $eventDate;

    /**
     * @var integer
     */
    private $eventType;

    /**
     * @var string
     */
    private $eventLocationAdminLevel1;

    /**
     * @var string
     */
    private $eventLocationAdminLevel2;

    /**
     * @var string
     */
    private $eventLocationCountry;

    /**
     * @var float
     */
    private $eventLocationLat;

    /**
     * @var float
     */
    private $eventLocationLong;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $userSurnames;

    /**
     * @var string
     */
    private $userPhone;

    /**
     * @var string
     */
    private $userEmail;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     */
    private $readAt;

    /**
     * @var \EVT\CoreDomainBundle\Entity\Showroom
     */
    private $showroom;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lead_information;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lead_information = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime(null, new \DateTimeZone('UTC'));
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set eventDate
     *
     * @param \DateTime $eventDate
     * @return Lead
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set eventType
     *
     * @param integer $eventType
     * @return Lead
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Get eventType
     *
     * @return integer
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Set eventLocationAdminLevel1
     *
     * @param string $eventLocationAdminLevel1
     * @return Lead
     */
    public function setEventLocationAdminLevel1($eventLocationAdminLevel1)
    {
        $this->eventLocationAdminLevel1 = $eventLocationAdminLevel1;

        return $this;
    }

    /**
     * Get eventLocationAdminLevel1
     *
     * @return string
     */
    public function getEventLocationAdminLevel1()
    {
        return $this->eventLocationAdminLevel1;
    }

    /**
     * Set eventLocationAdminLevel2
     *
     * @param string $eventLocationAdminLevel2
     * @return Lead
     */
    public function setEventLocationAdminLevel2($eventLocationAdminLevel2)
    {
        $this->eventLocationAdminLevel2 = $eventLocationAdminLevel2;

        return $this;
    }

    /**
     * Get eventLocationAdminLevel2
     *
     * @return string
     */
    public function getEventLocationAdminLevel2()
    {
        return $this->eventLocationAdminLevel2;
    }

    /**
     * Set eventLocationCountry
     *
     * @param string $eventLocationCountry
     * @return Lead
     */
    public function setEventLocationCountry($eventLocationCountry)
    {
        $this->eventLocationCountry = $eventLocationCountry;

        return $this;
    }

    /**
     * Get eventLocationCountry
     *
     * @return string
     */
    public function getEventLocationCountry()
    {
        return $this->eventLocationCountry;
    }

    /**
     * Set eventLocationLat
     *
     * @param float $eventLocationLat
     * @return Lead
     */
    public function setEventLocationLat($eventLocationLat)
    {
        $this->eventLocationLat = $eventLocationLat;

        return $this;
    }

    /**
     * Get eventLocationLat
     *
     * @return float
     */
    public function getEventLocationLat()
    {
        return $this->eventLocationLat;
    }

    /**
     * Set eventLocationLong
     *
     * @param float $eventLocationLong
     * @return Lead
     */
    public function setEventLocationLong($eventLocationLong)
    {
        $this->eventLocationLong = $eventLocationLong;

        return $this;
    }

    /**
     * Get eventLocationLong
     *
     * @return float
     */
    public function getEventLocationLong()
    {
        return $this->eventLocationLong;
    }

    /**
     * Set userName
     *
     * @param string $userName
     * @return Lead
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userSurnames
     *
     * @param string $userSurnames
     * @return Lead
     */
    public function setUserSurnames($userSurnames)
    {
        $this->userSurnames = $userSurnames;

        return $this;
    }

    /**
     * Get userSurnames
     *
     * @return string
     */
    public function getUserSurnames()
    {
        return $this->userSurnames;
    }

    /**
     * Set userPhone
     *
     * @param string $userPhone
     * @return Lead
     */
    public function setUserPhone($userPhone)
    {
        $this->userPhone = $userPhone;

        return $this;
    }

    /**
     * Get userPhone
     *
     * @return string
     */
    public function getUserPhone()
    {
        return $this->userPhone;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     * @return Lead
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Lead
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Lead
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set readAt
     *
     * @param \DateTime $readAt
     * @return Lead
     */
    public function setReadAt($readAt)
    {
        $this->readAt = $readAt;

        return $this;
    }

    /**
     * Get readAt
     *
     * @return \DateTime
     */
    public function getReadAt()
    {
        return $this->readAt;
    }

    /**
     * Set showroom
     *
     * @param \EVT\CoreDomainBundle\Entity\Showroom $showroom
     * @return Lead
     */
    public function setShowroom(Showroom $showroom = null)
    {
        $this->showroom = $showroom;

        return $this;
    }

    /**
     * Get showroom
     *
     * @return \EVT\CoreDomainBundle\Entity\Showroom
     */
    public function getShowroom()
    {
        return $this->showroom;
    }

    /**
     * Add lead_information
     *
     * @param \EVT\CoreDomainBundle\Model\LeadInformation $leadInformation
     * @return Lead
     */
    public function addLeadInformation(LeadInformation $leadInformation)
    {
        $this->lead_information[] = $leadInformation;

        return $this;
    }

    /**
     * Remove lead_information
     *
     * @param \EVT\CoreDomainBundle\Model\LeadInformation $leadInformation
     */
    public function removeLeadInformation(LeadInformation $leadInformation)
    {
        $this->lead_information->removeElement($leadInformation);
    }

    /**
     * Get lead_information
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLeadInformation()
    {
        return $this->lead_information;
    }
}
