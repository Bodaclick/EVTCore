<?php

namespace EVT\CoreDomainBundle\Events;

use EVT\CoreDomain\Lead\Lead;
use BDK\AsyncEventDispatcher\AsyncEventInterface;

/**
 * LeadEvent
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class LeadEvent implements AsyncEventInterface
{
    protected $lead;
    protected $name;

    /**
     * @param Showroom $showroom
     * @param string $name
     */
    public function __construct(Lead $lead, $name)
    {
        $this->lead = $lead;
        $this->name = $name;
    }

    /**
     * @return Lead
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
