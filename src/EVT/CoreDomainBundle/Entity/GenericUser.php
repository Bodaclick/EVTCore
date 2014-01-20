<?php

namespace EVT\CoreDomainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenericUser
 */
class GenericUser
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $surnames;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $provider;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->provider = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set email
     *
     * @param string $email
     * @return GenericUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return GenericUser
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
     * Set surnames
     *
     * @param string $surnames
     * @return GenericUser
     */
    public function setSurnames($surnames)
    {
        $this->surnames = $surnames;

        return $this;
    }

    /**
     * Get surnames
     *
     * @return string 
     */
    public function getSurnames()
    {
        return $this->surnames;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return GenericUser
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add provider
     *
     * @param \EVT\CoreDomainBundle\Entity\Provider $provider
     * @return GenericUser
     */
    public function addProvider(\EVT\CoreDomainBundle\Entity\Provider $provider)
    {
        $this->provider[] = $provider;

        return $this;
    }

    /**
     * Remove provider
     *
     * @param \EVT\CoreDomainBundle\Entity\Provider $provider
     */
    public function removeProvider(\EVT\CoreDomainBundle\Entity\Provider $provider)
    {
        $this->provider->removeElement($provider);
    }

    /**
     * Get provider
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
