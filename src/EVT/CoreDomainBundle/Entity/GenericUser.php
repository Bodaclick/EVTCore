<?php

namespace EVT\CoreDomainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * GenericUser
 */
class GenericUser extends BaseUSer
{
    /**
     * @var integer
     */
    protected $id;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $provider;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->provider = new ArrayCollection();
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
