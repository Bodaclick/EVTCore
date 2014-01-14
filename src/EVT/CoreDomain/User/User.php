<?php

namespace EVT\CoreDomain\User;

use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\InformationBag;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\Lead;

/**
 * User
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class User
{
    /**
     * doLead
     *
     * @param Showroom       $showroom
     * @param Event          $event
     * @param InformationBag $infoBag
     * @return Lead
     */
    public function doLead(Showroom $showroom, Event $event, InformationBag $infoBag)
    {
        $lead = new Lead($this, $showroom, $event);
        $lead->setInformationBag($infoBag);
        return $lead;
    }
}
