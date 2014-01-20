<?php

namespace EVT\CoreDomainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vertical
 */
class Vertical
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set domain
     *
     * @param string $domain
     * @return Vertical
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string 
     */
    public function getDomain()
    {
        return $this->domain;
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
}
