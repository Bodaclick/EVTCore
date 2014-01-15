<?php

namespace EVT\CoreDomain\User;

use EVT\CoreDomain\Lead\LeadId;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\InformationBag;
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
    public function doLead(Showroom $showroom, Event $event, InformationBag $infoBag = null)
    {
        $lead = new Lead(new LeadId(''), $this, $showroom, $event);

        $lead->setInformationBag($infoBag);
        return $lead;
    }
}
