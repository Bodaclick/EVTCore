<?php

namespace EVT\CoreDomainBundle\Entity;

/**
 * Provider
 */
class Provider
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $locationAdminLevel1;

    /**
     * @var string
     */
    private $locationAdminLevel2;

    /**
     * @var string
     */
    private $locationCountry;

    /**
     * @var float
     */
    private $locationLat;

    /**
     * @var float
     */
    private $locationLong;

    /**
     * @var EmailCollection
     */
    private $notificationEmails;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $genericUser;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->genericUser = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Provider
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Provider
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set locationAdminLevel1
     *
     * @param string $locationAdminLevel1
     * @return Provider
     */
    public function setLocationAdminLevel1($locationAdminLevel1)
    {
        $this->locationAdminLevel1 = $locationAdminLevel1;

        return $this;
    }

    /**
     * Get locationAdminLevel1
     *
     * @return string
     */
    public function getLocationAdminLevel1()
    {
        return $this->locationAdminLevel1;
    }

    /**
     * Set locationAdminLevel2
     *
     * @param string $locationAdminLevel2
     * @return Provider
     */
    public function setLocationAdminLevel2($locationAdminLevel2)
    {
        $this->locationAdminLevel2 = $locationAdminLevel2;

        return $this;
    }

    /**
     * Get locationAdminLevel2
     *
     * @return string
     */
    public function getLocationAdminLevel2()
    {
        return $this->locationAdminLevel2;
    }

    /**
     * Set locationCountry
     *
     * @param string $locationCountry
     * @return Provider
     */
    public function setLocationCountry($locationCountry)
    {
        $this->locationCountry = $locationCountry;

        return $this;
    }

    /**
     * Get locationCountry
     *
     * @return string
     */
    public function getLocationCountry()
    {
        return $this->locationCountry;
    }

    /**
     * Set locationLat
     *
     * @param float $locationLat
     * @return Provider
     */
    public function setLocationLat($locationLat)
    {
        $this->locationLat = $locationLat;

        return $this;
    }

    /**
     * Get locationLat
     *
     * @return float
     */
    public function getLocationLat()
    {
        return $this->locationLat;
    }

    /**
     * Set locationLong
     *
     * @param float $locationLong
     * @return Provider
     */
    public function setLocationLong($locationLong)
    {
        $this->locationLong = $locationLong;

        return $this;
    }

    /**
     * Get locationLong
     *
     * @return float
     */
    public function getLocationLong()
    {
        return $this->locationLong;
    }

    /**
     * Set notificationEmails
     *
     * @param string $notificationEmails
     * @return Provider
     */
    public function setNotificationEmails($notificationEmails)
    {
        $this->notificationEmails = $notificationEmails;

        return $this;
    }

    /**
     * Get notificationEmails
     *
     * @return string
     */
    public function getNotificationEmails()
    {
        return $this->notificationEmails;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Provider
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add genericUser
     *
     * @param \EVT\CoreDomainBundle\Entity\GenericUser $genericUser
     * @return Provider
     */
    public function addGenericUser(\EVT\CoreDomainBundle\Entity\GenericUser $genericUser)
    {
        $this->genericUser[] = $genericUser;

        return $this;
    }

    /**
     * Remove genericUser
     *
     * @param \EVT\CoreDomainBundle\Entity\GenericUser $genericUser
     */
    public function removeGenericUser(\EVT\CoreDomainBundle\Entity\GenericUser $genericUser)
    {
        $this->genericUser->removeElement($genericUser);
    }

    /**
     * Get genericUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGenericUser()
    {
        return $this->genericUser;
    }
}
