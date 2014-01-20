<?php

namespace EVT\CoreDomainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Showroom
 */
class Showroom
{
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
    private $slug;

    /**
     * @var float
     */
    private $score;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \EVT\CoreDomainBundle\Entity\Provider
     */
    private $provider;

    /**
     * @var \EVT\CoreDomainBundle\Entity\Vertical
     */
    private $vertical;


    /**
     * Set name
     *
     * @param string $name
     * @return Showroom
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
     * @return Showroom
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
     * Set slug
     *
     * @param string $slug
     * @return Showroom
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
     * Set score
     *
     * @param float $score
     * @return Showroom
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return float 
     */
    public function getScore()
    {
        return $this->score;
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
     * Set provider
     *
     * @param \EVT\CoreDomainBundle\Entity\Provider $provider
     * @return Showroom
     */
    public function setProvider(\EVT\CoreDomainBundle\Entity\Provider $provider = null)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return \EVT\CoreDomainBundle\Entity\Provider 
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set vertical
     *
     * @param \EVT\CoreDomainBundle\Entity\Vertical $vertical
     * @return Showroom
     */
    public function setVertical(\EVT\CoreDomainBundle\Entity\Vertical $vertical = null)
    {
        $this->vertical = $vertical;

        return $this;
    }

    /**
     * Get vertical
     *
     * @return \EVT\CoreDomainBundle\Entity\Vertical 
     */
    public function getVertical()
    {
        return $this->vertical;
    }
}
