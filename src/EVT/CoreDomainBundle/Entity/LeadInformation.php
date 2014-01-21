<?php

namespace EVT\CoreDomainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LeadInformation
 */
class LeadInformation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \EVT\CoreDomainBundle\Entity\Lead
     */
    private $lead;


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
     * Set key
     *
     * @param string $key
     * @return LeadInformation
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return LeadInformation
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set lead
     *
     * @param \EVT\CoreDomainBundle\Entity\Lead $lead
     * @return LeadInformation
     */
    public function setLead(\EVT\CoreDomainBundle\Entity\Lead $lead = null)
    {
        $this->lead = $lead;

        return $this;
    }

    /**
     * Get lead
     *
     * @return \EVT\CoreDomainBundle\Entity\Lead 
     */
    public function getLead()
    {
        return $this->lead;
    }
}
