<?php

namespace EVT\CoreDomain\User;

use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\Lead\LeadInformationBag;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\Lead;

/**
 * User
 *
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class User extends GenericUser
{
    /**
     * doLead
     *
     * @param Showroom       $showroom
     * @param Event          $event
     * @param InformationBag $infoBag
     * @return Lead
     */
    public function doLead(Showroom $showroom, Event $event, LeadInformationBag $infoBag = null)
    {
        $lead = new Lead(new LeadId(''), $this->personalInfo, $this->email, $showroom, $event);
        if (null !== $infoBag) {
            $lead->setInformationBag($infoBag);
        }

        return $lead;
    }
}
